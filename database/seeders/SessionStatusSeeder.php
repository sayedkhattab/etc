<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SessionStatus;

class SessionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'مجدولة',
                'description' => 'جلسة تم جدولتها',
                'color' => 'primary',
            ],
            [
                'name' => 'قادمة',
                'description' => 'جلسة قادمة',
                'color' => 'info',
            ],
            [
                'name' => 'جارية',
                'description' => 'جلسة جارية حالياً',
                'color' => 'warning',
            ],
            [
                'name' => 'مكتملة',
                'description' => 'جلسة مكتملة',
                'color' => 'success',
            ],
            [
                'name' => 'ملغية',
                'description' => 'جلسة ملغية',
                'color' => 'danger',
            ],
            [
                'name' => 'مؤجلة',
                'description' => 'جلسة مؤجلة',
                'color' => 'secondary',
            ],
        ];

        foreach ($statuses as $status) {
            SessionStatus::create($status);
        }
    }
} 