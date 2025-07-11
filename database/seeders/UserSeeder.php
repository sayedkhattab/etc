<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $instructorRole = Role::where('name', 'instructor')->first();
        $judgeRole = Role::where('name', 'judge')->first();
        $lawyerRole = Role::where('name', 'lawyer')->first();
        $studentRole = Role::where('name', 'student')->first();
        
        // Create admin user
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@ithbat.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        UserProfile::create([
            'user_id' => $admin->id,
            'phone' => '966500000000',
            'address' => 'الرياض، المملكة العربية السعودية',
            'bio' => 'مدير نظام منصة إثبات للتعليم القانوني',
            'avatar' => null,
        ]);
        
        // Create instructor users
        $instructor1 = User::create([
            'name' => 'د. أحمد محمود',
            'email' => 'ahmed@ithbat.com',
            'password' => Hash::make('password'),
            'role_id' => $instructorRole->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        UserProfile::create([
            'user_id' => $instructor1->id,
            'phone' => '966500000001',
            'address' => 'جدة، المملكة العربية السعودية',
            'bio' => 'دكتوراه في القانون الجنائي، خبرة تدريسية 15 عاماً',
            'avatar' => null,
            'specialization' => 'القانون الجنائي',
            'education' => 'دكتوراه في القانون - جامعة القاهرة',
        ]);
        
        $instructor2 = User::create([
            'name' => 'د. سارة الخالدي',
            'email' => 'sara@ithbat.com',
            'password' => Hash::make('password'),
            'role_id' => $instructorRole->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        UserProfile::create([
            'user_id' => $instructor2->id,
            'phone' => '966500000002',
            'address' => 'الرياض، المملكة العربية السعودية',
            'bio' => 'متخصصة في القانون المدني والتجاري، خبرة 10 سنوات',
            'avatar' => null,
            'specialization' => 'القانون المدني',
            'education' => 'دكتوراه في القانون - جامعة الملك سعود',
        ]);
        
        // Create judge users
        $judge = User::create([
            'name' => 'المستشار محمد العتيبي',
            'email' => 'judge@ithbat.com',
            'password' => Hash::make('password'),
            'role_id' => $judgeRole->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        UserProfile::create([
            'user_id' => $judge->id,
            'phone' => '966500000003',
            'address' => 'الرياض، المملكة العربية السعودية',
            'bio' => 'قاضي بمحكمة الاستئناف، خبرة 20 عاماً في القضاء',
            'avatar' => null,
            'specialization' => 'القانون التجاري',
            'education' => 'ماجستير في القانون - جامعة الإمام محمد بن سعود',
        ]);
        
        // Create lawyer users
        $lawyer = User::create([
            'name' => 'عبدالله الشمري',
            'email' => 'lawyer@ithbat.com',
            'password' => Hash::make('password'),
            'role_id' => $lawyerRole->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        UserProfile::create([
            'user_id' => $lawyer->id,
            'phone' => '966500000004',
            'address' => 'الدمام، المملكة العربية السعودية',
            'bio' => 'محامي ومستشار قانوني، خبرة 12 عاماً',
            'avatar' => null,
            'specialization' => 'قانون الشركات',
            'education' => 'بكالوريوس في القانون - جامعة الملك فيصل',
        ]);
        
        // Create student users
        for ($i = 1; $i <= 5; $i++) {
            $student = User::create([
                'name' => "طالب $i",
                'email' => "student$i@ithbat.com",
                'password' => Hash::make('password'),
                'role_id' => $studentRole->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            
            UserProfile::create([
                'user_id' => $student->id,
                'phone' => "96650000100$i",
                'address' => 'المملكة العربية السعودية',
                'bio' => 'طالب في كلية الحقوق',
                'avatar' => null,
                'education' => 'بكالوريوس في القانون',
            ]);
        }
    }
} 