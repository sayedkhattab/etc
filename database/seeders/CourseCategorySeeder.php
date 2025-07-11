<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseCategory;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'القانون الجنائي',
                'description' => 'دورات متخصصة في القانون الجنائي والإجراءات الجزائية',
                'icon' => 'fa-gavel',
            ],
            [
                'name' => 'القانون المدني',
                'description' => 'دورات متخصصة في القانون المدني والعقود والالتزامات',
                'icon' => 'fa-file-contract',
            ],
            [
                'name' => 'قانون الشركات',
                'description' => 'دورات متخصصة في قانون الشركات والتجارة',
                'icon' => 'fa-building',
            ],
            [
                'name' => 'القانون الدولي',
                'description' => 'دورات متخصصة في القانون الدولي العام والخاص',
                'icon' => 'fa-globe',
            ],
            [
                'name' => 'قانون العمل',
                'description' => 'دورات متخصصة في قانون العمل والعمال',
                'icon' => 'fa-briefcase',
            ],
            [
                'name' => 'الملكية الفكرية',
                'description' => 'دورات متخصصة في حقوق الملكية الفكرية وبراءات الاختراع',
                'icon' => 'fa-lightbulb',
            ],
            [
                'name' => 'المهارات القانونية',
                'description' => 'دورات لتطوير المهارات القانونية العملية مثل الصياغة والمرافعة',
                'icon' => 'fa-balance-scale',
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
} 