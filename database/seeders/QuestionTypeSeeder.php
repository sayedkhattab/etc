<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionType;

class QuestionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Multiple Choice', 'description' => 'سؤال اختيار من متعدد'],
            ['name' => 'True/False',     'description' => 'سؤال صح أو خطأ'],
            ['name' => 'Short Answer',   'description' => 'إجابة قصيرة'],
        ];

        foreach ($types as $type) {
            QuestionType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
} 