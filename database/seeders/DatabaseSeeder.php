<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // الأدوار والمستخدمين
            RoleSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            
            // المنصة التعليمية
            CourseCategorySeeder::class,
            ContentTypeSeeder::class,
            AssessmentTypeSeeder::class,
            QuestionTypeSeeder::class,
            
            // المحكمة الافتراضية
            CaseStatusSeeder::class,
            CourtTypeSeeder::class,
            SessionTypeSeeder::class,
            SessionStatusSeeder::class,
            JudgmentTypeSeeder::class,
            
            // متجر القضايا
            StoreCaseCategorySeeder::class,
            QuestionTypeSeeder::class,
        ]);
    }
}
