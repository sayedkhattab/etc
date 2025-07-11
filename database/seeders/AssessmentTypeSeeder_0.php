<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة أنواع التقييمات (بدون تفريغ الجدول)
        DB::table('assessment_types')->insert([
            [
                'name' => 'تحديد مستوى',
                'description' => 'تقييم لتحديد مستوى الطالب',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'تقييم للدرس',
                'description' => 'تقييم لقياس فهم الطالب للدرس',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'اختبار نهائي',
                'description' => 'اختبار نهائي للدورة التدريبية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 