<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة أنواع الأسئلة (بدون تفريغ الجدول)
        DB::table('question_types')->insert([
            [
                'name' => 'اختيار من متعدد',
                'description' => 'سؤال مع خيارات متعددة للإجابة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'صح وخطأ',
                'description' => 'سؤال مع خيارين فقط: صح أو خطأ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'إجابة قصيرة',
                'description' => 'سؤال يتطلب إجابة قصيرة مكتوبة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مقالي',
                'description' => 'سؤال يتطلب إجابة مطولة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ترتيب',
                'description' => 'سؤال يتطلب ترتيب العناصر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 