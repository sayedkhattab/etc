<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // تفريغ الجداول قبل إضافة البيانات الجديدة
        $this->truncateTables();
        
        // إنشاء الأدوار
        $this->call(RoleSeeder::class);
        
        // إنشاء المسؤولين
        $this->call(AdminSeeder::class);
        
        // إضافة أنواع التقييمات
        $this->call(AssessmentTypeSeeder::class);
        
        // إضافة أنواع المحتوى
        $this->call(ContentTypeSeeder::class);
        
        // إضافة أنواع الأسئلة
        $this->call(QuestionTypeSeeder::class);
    }

    /**
     * تفريغ الجداول قبل إضافة البيانات الجديدة
     */
    protected function truncateTables(): void
    {
        // تعطيل فحص المفاتيح الخارجية
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // تفريغ الجداول
        \DB::table('admins')->truncate();
        \DB::table('roles')->truncate();
        \DB::table('assessment_types')->truncate();
        \DB::table('content_types')->truncate();
        \DB::table('question_types')->truncate();
        
        // إعادة تفعيل فحص المفاتيح الخارجية
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
