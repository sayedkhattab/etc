<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء دور المسؤول الرئيسي (Super Admin)
        Role::create([
            'name' => 'admin',
            'description' => 'مسؤول النظام الرئيسي',
            'permissions' => ['*'], // كل الصلاحيات
        ]);

        // إنشاء دور مسؤول المحتوى
        Role::create([
            'name' => 'content_manager',
            'description' => 'مسؤول المحتوى',
            'permissions' => [
                'manage_courses', 
                'manage_content', 
                'view_reports', 
                'manage_certificates',
                'manage_assessments',
            ],
        ]);

        // إنشاء دور مسؤول القضايا
        Role::create([
            'name' => 'case_manager',
            'description' => 'مسؤول القضايا',
            'permissions' => [
                'manage_cases', 
                'manage_sessions', 
                'view_reports', 
                'manage_court_archives',
                'manage_judgments',
            ],
        ]);
        
        // إنشاء دور مسؤول المستخدمين
        Role::create([
            'name' => 'user_manager',
            'description' => 'مسؤول المستخدمين',
            'permissions' => [
                'manage_users', 
                'view_reports', 
                'manage_payments',
            ],
        ]);
    }
} 