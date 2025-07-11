<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JudgmentType;

class JudgmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'حكم ابتدائي',
                'description' => 'حكم ابتدائي قابل للاستئناف',
            ],
            [
                'name' => 'حكم نهائي',
                'description' => 'حكم نهائي غير قابل للاستئناف',
            ],
            [
                'name' => 'حكم غيابي',
                'description' => 'حكم غيابي في غياب أحد الأطراف',
            ],
            [
                'name' => 'حكم استئناف',
                'description' => 'حكم محكمة الاستئناف',
            ],
            [
                'name' => 'حكم تمهيدي',
                'description' => 'حكم تمهيدي قبل الفصل في الموضوع',
            ],
            [
                'name' => 'حكم تكميلي',
                'description' => 'حكم تكميلي للحكم الأصلي',
            ],
        ];

        foreach ($types as $type) {
            JudgmentType::create($type);
        }
    }
} 