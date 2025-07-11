<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransferDataSafely extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:transfer-safely {--backup : Create backup before transfer} {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely transfer all data while preserving table structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('confirm')) {
            if (!$this->confirm('هل أنت متأكد من نقل البيانات؟ سيتم حذف بيانات جدول الطلبات فقط.')) {
                $this->info('تم إلغاء العملية.');
                return 0;
            }
        }

        $this->info('بدء عملية نقل البيانات الآمنة...');

        try {
            // إنشاء نسخة احتياطية إذا طُلب
            if ($this->option('backup')) {
                $this->createBackup();
            }

            // الحصول على قائمة الجداول
            $tables = $this->getTables();
            $this->info('تم العثور على ' . count($tables) . ' جدول');

            // إنشاء نسخ احتياطية من البيانات
            $this->info('إنشاء نسخ احتياطية من البيانات...');
            $backupTables = $this->backupTables($tables);

            // حذف بيانات جدول الطلبات فقط
            $this->info('حذف بيانات جدول الطلبات...');
            if (Schema::hasTable('orders')) {
                $count = DB::table('orders')->count();
                if ($count > 0) {
                    DB::table('orders')->truncate();
                    $this->info("تم حذف {$count} طلب من جدول الطلبات");
                } else {
                    $this->info('جدول الطلبات فارغ بالفعل');
                }
            }

            // استعادة البيانات من النسخ الاحتياطية
            $this->info('استعادة البيانات...');
            $this->restoreTables($backupTables);

            // تنظيف الجداول المؤقتة
            $this->cleanup($backupTables);

            $this->info('تم نقل البيانات بنجاح!');
            $this->info('تم حذف بيانات جدول الطلبات فقط، جميع الجداول الأخرى محفوظة');

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
            // تخطي جداول النظام
            if (!str_starts_with($tableName, 'migrations') && 
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
            // تخطي جدول الطلبات في الاستعادة
            if ($originalTable === 'orders') {
                $this->line("تخطي استعادة جدول: {$originalTable}");
                continue;
            }
            
            $this->line("استعادة بيانات جدول: {$originalTable}");
            
            try {
                // حذف البيانات الموجودة مع تجاهل القيود الخارجية
                DB::statement("SET FOREIGN_KEY_CHECKS = 0");
                DB::table($originalTable)->truncate();
                DB::statement("SET FOREIGN_KEY_CHECKS = 1");
                
                // استعادة البيانات
                DB::statement("INSERT INTO {$originalTable} SELECT * FROM {$backupTable}");
            } catch (\Exception $e) {
                $this->warn("تحذير: فشل في استعادة جدول {$originalTable}: " . $e->getMessage());
            }
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