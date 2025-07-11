<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SessionType;

class SessionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'جلسة تحضيرية',
                'description' => 'جلسة تحضيرية للقضية',
            ],
            [
                'name' => 'جلسة استماع',
                'description' => 'جلسة استماع للأطراف',
            ],
            [
                'name' => 'جلسة مرافعة',
                'description' => 'جلسة مرافعة',
            ],
            [
                'name' => 'جلسة استجواب',
                'description' => 'جلسة استجواب للشهود',
            ],
            [
                'name' => 'جلسة مداولة',
                'description' => 'جلسة مداولة بين القضاة',
            ],
            [
                'name' => 'جلسة النطق بالحكم',
                'description' => 'جلسة النطق بالحكم',
            ],
        ];

        foreach ($types as $type) {
            SessionType::create($type);
        }
    }
} 