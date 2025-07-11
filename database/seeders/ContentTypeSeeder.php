<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContentType;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'فيديو',
                'description' => 'محتوى فيديو تعليمي',
                'icon' => 'fa-video',
            ],
            [
                'name' => 'نص',
                'description' => 'محتوى نصي',
                'icon' => 'fa-file-alt',
            ],
            [
                'name' => 'عرض تقديمي',
                'description' => 'عرض تقديمي (شرائح)',
                'icon' => 'fa-file-powerpoint',
            ],
            [
                'name' => 'ملف PDF',
                'description' => 'ملف PDF للقراءة',
                'icon' => 'fa-file-pdf',
            ],
            [
                'name' => 'رابط خارجي',
                'description' => 'رابط لمصدر خارجي',
                'icon' => 'fa-external-link-alt',
            ],
            [
                'name' => 'تفاعلي',
                'description' => 'محتوى تفاعلي',
                'icon' => 'fa-hands-helping',
            ],
        ];

        foreach ($types as $type) {
            ContentType::create($type);
        }
    }
} 