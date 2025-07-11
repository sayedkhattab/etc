<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearOrdersTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:clear {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all data from orders table and recreate it empty';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('confirm')) {
            if (!$this->confirm('هل أنت متأكد من حذف جميع بيانات جدول الطلبات؟ هذا الإجراء لا يمكن التراجع عنه.')) {
                $this->info('تم إلغاء العملية.');
                return 0;
            }
        }

        $this->info('بدء عملية حذف بيانات جدول الطلبات...');

        try {
            // التحقق من وجود جدول الطلبات
            if (!Schema::hasTable('orders')) {
                $this->error('جدول الطلبات غير موجود!');
                return 1;
            }

            // حذف جميع البيانات من جدول الطلبات
            $count = DB::table('orders')->count();
            $this->info("تم العثور على {$count} طلب في الجدول");

            if ($count > 0) {
                DB::table('orders')->truncate();
                $this->info('تم حذف جميع بيانات جدول الطلبات بنجاح');
            } else {
                $this->info('جدول الطلبات فارغ بالفعل');
            }

            $this->info('تم الانتهاء من العملية بنجاح!');
            $this->info('جدول الطلبات الآن فارغ وجاهز للاستخدام');

        } catch (\Exception $e) {
            $this->error('حدث خطأ أثناء حذف البيانات: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 