<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransferDataExceptOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:transfer-except-orders {--backup : Create backup before transfer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer all data except orders table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء عملية نقل البيانات مع تخطي جدول الطلبات...');

        // إنشاء نسخة احتياطية إذا طُلب
        if ($this->option('backup')) {
            $this->createBackup();
        }

        try {
            // الحصول على قائمة الجداول
            $tables = $this->getTables();
            
            $this->info('تم العثور على ' . count($tables) . ' جدول');

            // إنشاء نسخة احتياطية من البيانات
            $this->info('إنشاء نسخ احتياطية من البيانات...');
            $backupTables = $this->backupTables($tables);

            // حذف جدول الطلبات
            $this->info('حذف جدول الطلبات...');
            if (Schema::hasTable('orders')) {
                Schema::dropIfExists('orders');
                $this->info('تم حذف جدول الطلبات');
            }

            // إعادة إنشاء جدول الطلبات فارغ
            $this->info('إعادة إنشاء جدول الطلبات...');
            Schema::create('orders', function ($table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('course_id')->constrained()->cascadeOnDelete();
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_status')->default('pending');
                $table->string('payment_reference')->nullable();
                $table->timestamps();
            });

            // استعادة البيانات
            $this->info('استعادة البيانات...');
            $this->restoreTables($backupTables);

            // تنظيف الجداول المؤقتة
            $this->cleanup($backupTables);

            $this->info('تم نقل البيانات بنجاح!');
            $this->info('تم حذف جميع بيانات جدول الطلبات وإعادة إنشاؤه فارغاً');

        } catch (\Exception $e) {
            $this->error('حدث خطأ أثناء نقل البيانات: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * الحصول على قائمة الجداول
     */
    private function getTables(): array
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            // تخطي جدول الطلبات وجداول النظام
            if ($tableName !== 'orders' && 
                !str_starts_with($tableName, 'migrations') && 
                !str_starts_with($tableName, 'failed_jobs') &&
                !str_starts_with($tableName, 'password_reset_tokens') &&
                !str_starts_with($tableName, 'personal_access_tokens')) {
                $tableNames[] = $tableName;
            }
        }

        return $tableNames;
    }

    /**
     * إنشاء نسخة احتياطية من الجداول
     */
    private function backupTables(array $tables): array
    {
        $backupTables = [];

        foreach ($tables as $tableName) {
            $backupTableName = $tableName . '_backup_' . time();
            
            $this->line("إنشاء نسخة احتياطية من جدول: {$tableName}");
            
            // نسخ هيكل الجدول
            DB::statement("CREATE TABLE {$backupTableName} LIKE {$tableName}");
            
            // نسخ البيانات
            DB::statement("INSERT INTO {$backupTableName} SELECT * FROM {$tableName}");
            
            $backupTables[$tableName] = $backupTableName;
        }

        return $backupTables;
    }

    /**
     * استعادة البيانات من النسخ الاحتياطية
     */
    private function restoreTables(array $backupTables): void
    {
        foreach ($backupTables as $originalTable => $backupTable) {
            $this->line("استعادة بيانات جدول: {$originalTable}");
            
            // حذف البيانات الموجودة
            DB::table($originalTable)->truncate();
            
            // استعادة البيانات
            DB::statement("INSERT INTO {$originalTable} SELECT * FROM {$backupTable}");
        }
    }

    /**
     * تنظيف الجداول المؤقتة
     */
    private function cleanup(array $backupTables): void
    {
        $this->info('تنظيف الجداول المؤقتة...');
        
        foreach ($backupTables as $backupTable) {
            DB::statement("DROP TABLE {$backupTable}");
        }
    }

    /**
     * إنشاء نسخة احتياطية كاملة
     */
    private function createBackup(): void
    {
        $backupName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $this->info("إنشاء نسخة احتياطية: {$backupName}");
        
        // يمكن إضافة أمر mysqldump هنا إذا كان متاحاً
        $this->warn('يرجى إنشاء نسخة احتياطية يدوياً قبل المتابعة');
    }
} 