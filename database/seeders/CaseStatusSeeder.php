<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CaseStatus;

class CaseStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'جديدة',
                'description' => 'قضية جديدة تم إنشاؤها',
                'color' => 'primary',
            ],
            [
                'name' => 'قيد النظر',
                'description' => 'قضية قيد النظر حالياً',
                'color' => 'info',
            ],
            [
                'name' => 'معلقة',
                'description' => 'قضية معلقة بانتظار إجراء',
                'color' => 'warning',
            ],
            [
                'name' => 'مكتملة',
                'description' => 'قضية مكتملة وتم إصدار الحكم',
                'color' => 'success',
            ],
            [
                'name' => 'مغلقة',
                'description' => 'قضية مغلقة',
                'color' => 'secondary',
            ],
            [
                'name' => 'مؤرشفة',
                'description' => 'قضية تمت أرشفتها',
                'color' => 'dark',
            ],
        ];

        foreach ($statuses as $status) {
            CaseStatus::create($status);
        }
    }
} 