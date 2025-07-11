<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssessmentType;

class AssessmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'اختبار',
                'description' => 'اختبار تقييمي للطلاب',
                'icon' => 'fa-tasks',
            ],
            [
                'name' => 'واجب',
                'description' => 'واجب منزلي للطلاب',
                'icon' => 'fa-home',
            ],
            [
                'name' => 'مشروع',
                'description' => 'مشروع تطبيقي',
                'icon' => 'fa-project-diagram',
            ],
            [
                'name' => 'مناقشة',
                'description' => 'مناقشة تفاعلية',
                'icon' => 'fa-comments',
            ],
            [
                'name' => 'اختبار نهائي',
                'description' => 'اختبار نهائي للدورة',
                'icon' => 'fa-clipboard-check',
            ],
        ];

        foreach ($types as $type) {
            AssessmentType::create($type);
        }
    }
} 