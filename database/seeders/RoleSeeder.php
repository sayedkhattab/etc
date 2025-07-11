<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'مدير النظام',
                'permissions' => [
                    'manage_users',
                    'manage_roles',
                    'manage_courses',
                    'manage_assessments',
                    'manage_certificates',
                    'manage_cases',
                    'manage_sessions',
                    'manage_judgments',
                    'view_reports',
                    'manage_settings'
                ]
            ],
            [
                'name' => 'instructor',
                'description' => 'مدرس',
                'permissions' => [
                    'manage_courses',
                    'manage_assessments',
                    'manage_certificates',
                    'view_student_progress'
                ]
            ],
            [
                'name' => 'judge',
                'description' => 'قاضي',
                'permissions' => [
                    'manage_cases',
                    'manage_sessions',
                    'manage_judgments',
                    'review_defense_entries'
                ]
            ],
            [
                'name' => 'lawyer',
                'description' => 'محامي',
                'permissions' => [
                    'participate_in_cases',
                    'submit_defense_entries',
                    'attend_sessions'
                ]
            ],
            [
                'name' => 'student',
                'description' => 'طالب',
                'permissions' => [
                    'enroll_in_courses',
                    'take_assessments',
                    'participate_in_cases',
                    'submit_defense_entries',
                    'attend_sessions'
                ]
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
} 