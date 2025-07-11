<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة أنواع المحتوى (بدون تفريغ الجدول)
        DB::table('content_types')->insert([
            [
                'name' => 'فيديو',
                'description' => 'محتوى فيديو',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'صوت',
                'description' => 'محتوى صوتي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ملف PDF',
                'description' => 'محتوى ملف PDF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 