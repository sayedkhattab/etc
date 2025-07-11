<?php

namespace Database\Seeders;

use App\Models\Store\StoreCaseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreCaseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'قضايا مدنية',
                'description' => 'قضايا تتعلق بالنزاعات المدنية بين الأفراد والشركات',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'قضايا تجارية',
                'description' => 'قضايا تتعلق بالنزاعات التجارية بين الشركات والمؤسسات',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'قضايا عمالية',
                'description' => 'قضايا تتعلق بنزاعات العمل والعمال',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'قضايا أحوال شخصية',
                'description' => 'قضايا تتعلق بالأحوال الشخصية مثل الزواج والطلاق والميراث',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'قضايا عقارية',
                'description' => 'قضايا تتعلق بالنزاعات العقارية والملكية',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'قضايا إدارية',
                'description' => 'قضايا تتعلق بالنزاعات الإدارية مع الجهات الحكومية',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            StoreCaseCategory::create($category);
        }
    }
}
