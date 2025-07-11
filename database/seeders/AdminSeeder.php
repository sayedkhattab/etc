<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مسؤول النظام الرئيسي (Super Admin)
        Admin::create([
            'name' => 'مدير النظام',
            'email' => 'admin@ithbat.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'phone' => '0123456789',
            'role_id' => Role::where('name', 'admin')->first()->id,
            'status' => 'active',
            'permissions' => ['*'], // كل الصلاحيات
            'email_verified_at' => now(),
        ]);

        // إنشاء مسؤول المحتوى
        Admin::create([
            'name' => 'مسؤول المحتوى',
            'email' => 'content@ithbat.com',
            'username' => 'content',
            'password' => Hash::make('content123'),
            'phone' => '0123456788',
            'role_id' => Role::where('name', 'admin')->first()->id,
            'status' => 'active',
            'permissions' => ['manage_courses', 'manage_content', 'view_reports', 'manage_certificates', 'manage_assessments'],
            'email_verified_at' => now(),
        ]);

        // إنشاء مسؤول القضايا
        Admin::create([
            'name' => 'مسؤول القضايا',
            'email' => 'cases@ithbat.com',
            'username' => 'cases',
            'password' => Hash::make('cases123'),
            'phone' => '0123456787',
            'role_id' => Role::where('name', 'admin')->first()->id,
            'status' => 'active',
            'permissions' => ['manage_cases', 'manage_sessions', 'view_reports', 'manage_court_archives', 'manage_judgments'],
            'email_verified_at' => now(),
        ]);
        
        // إنشاء مسؤول المستخدمين
        Admin::create([
            'name' => 'مسؤول المستخدمين',
            'email' => 'users@ithbat.com',
            'username' => 'users',
            'password' => Hash::make('users123'),
            'phone' => '0123456786',
            'role_id' => Role::where('name', 'admin')->first()->id,
            'status' => 'active',
            'permissions' => ['manage_users', 'view_reports', 'manage_payments'],
            'email_verified_at' => now(),
        ]);

        // إنشاء مسؤولين إضافيين باستخدام المصنع
        Admin::factory()->count(3)->create();
    }
}
