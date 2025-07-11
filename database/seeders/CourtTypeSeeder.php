<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourtType;

class CourtTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'المحكمة الجزائية',
                'description' => 'المحكمة المختصة بالقضايا الجزائية',
            ],
            [
                'name' => 'المحكمة المدنية',
                'description' => 'المحكمة المختصة بالقضايا المدنية',
            ],
            [
                'name' => 'المحكمة التجارية',
                'description' => 'المحكمة المختصة بالقضايا التجارية',
            ],
            [
                'name' => 'محكمة الأحوال الشخصية',
                'description' => 'المحكمة المختصة بقضايا الأحوال الشخصية',
            ],
            [
                'name' => 'المحكمة العمالية',
                'description' => 'المحكمة المختصة بالقضايا العمالية',
            ],
            [
                'name' => 'المحكمة الإدارية',
                'description' => 'المحكمة المختصة بالقضايا الإدارية',
            ],
            [
                'name' => 'محكمة الاستئناف',
                'description' => 'محكمة الاستئناف',
            ],
            [
                'name' => 'المحكمة العليا',
                'description' => 'المحكمة العليا',
            ],
        ];

        foreach ($types as $type) {
            CourtType::create($type);
        }
    }
} 