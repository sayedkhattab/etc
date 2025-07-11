<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Assessment;
use App\Models\AssessmentType;

class PretestQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el tipo de pregunta de opción múltiple
        $multipleChoiceType = QuestionType::where('name', 'اختيار من متعدد')
            ->orWhere('name', 'multiple_choice')
            ->first();
            
        if (!$multipleChoiceType) {
            $multipleChoiceType = QuestionType::create([
                'name' => 'اختيار من متعدد',
                'description' => 'سؤال اختيار من متعدد'
            ]);
        }
        
        $questionTypeId = $multipleChoiceType->id;
        $levelId = 11; // ID del nivel que mencionaste
        
        // Crear o encontrar un tipo de evaluación para pretest
        $assessmentType = AssessmentType::where('name', 'اختبار تحديد المستوى')
            ->orWhere('name', 'pre-test')
            ->first();
            
        if (!$assessmentType) {
            $assessmentType = AssessmentType::create([
                'name' => 'اختبار تحديد المستوى',
                'description' => 'اختبار لتحديد مستوى الطالب قبل بدء المحتوى'
            ]);
        }
        
        // Crear o encontrar una evaluación para este nivel
        $assessment = Assessment::where('level_id', $levelId)
            ->where('is_pretest', true)
            ->first();
            
        if (!$assessment) {
            $assessment = Assessment::create([
                'level_id' => $levelId,
                'assessment_type_id' => $assessmentType->id,
                'title' => 'اختبار تحديد المستوى',
                'description' => 'اختبار لتحديد مستوى الطالب قبل بدء المحتوى',
                'passing_score' => 70,
                'time_limit' => 30,
                'is_active' => true,
                'is_pretest' => true,
            ]);
        }
        
        // Crear preguntas de prueba
        $questions = [
            [
                'question_text' => 'ما هو أول حرف في الأبجدية العربية؟',
                'options' => [
                    ['option_text' => 'أ', 'is_correct' => true],
                    ['option_text' => 'ب', 'is_correct' => false],
                    ['option_text' => 'ت', 'is_correct' => false],
                    ['option_text' => 'ث', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'كم عدد الحروف في اللغة العربية؟',
                'options' => [
                    ['option_text' => '26', 'is_correct' => false],
                    ['option_text' => '28', 'is_correct' => true],
                    ['option_text' => '29', 'is_correct' => false],
                    ['option_text' => '30', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'ما هو جمع كلمة "كتاب"؟',
                'options' => [
                    ['option_text' => 'كتابات', 'is_correct' => false],
                    ['option_text' => 'كتب', 'is_correct' => true],
                    ['option_text' => 'كاتبون', 'is_correct' => false],
                    ['option_text' => 'مكتبات', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'أي من الكلمات التالية هي اسم؟',
                'options' => [
                    ['option_text' => 'يذهب', 'is_correct' => false],
                    ['option_text' => 'سريعاً', 'is_correct' => false],
                    ['option_text' => 'محمد', 'is_correct' => true],
                    ['option_text' => 'لم', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'ما هو الفعل المضارع من "كتب"؟',
                'options' => [
                    ['option_text' => 'كاتب', 'is_correct' => false],
                    ['option_text' => 'يكتب', 'is_correct' => true],
                    ['option_text' => 'اكتب', 'is_correct' => false],
                    ['option_text' => 'كتاب', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'ما هو ضد كلمة "طويل"؟',
                'options' => [
                    ['option_text' => 'قصير', 'is_correct' => true],
                    ['option_text' => 'عريض', 'is_correct' => false],
                    ['option_text' => 'كبير', 'is_correct' => false],
                    ['option_text' => 'سميك', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'أي من الجمل التالية صحيحة نحوياً؟',
                'options' => [
                    ['option_text' => 'ذهب الطالب إلى المدرسة', 'is_correct' => true],
                    ['option_text' => 'ذهب الطالب على المدرسة', 'is_correct' => false],
                    ['option_text' => 'ذهب الطالب في المدرسة', 'is_correct' => false],
                    ['option_text' => 'ذهب الطالب من المدرسة', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'ما هي علامة رفع الاسم المفرد؟',
                'options' => [
                    ['option_text' => 'الفتحة', 'is_correct' => false],
                    ['option_text' => 'الكسرة', 'is_correct' => false],
                    ['option_text' => 'الضمة', 'is_correct' => true],
                    ['option_text' => 'السكون', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'ما هو الفاعل في جملة "قرأ محمد الكتاب"؟',
                'options' => [
                    ['option_text' => 'قرأ', 'is_correct' => false],
                    ['option_text' => 'محمد', 'is_correct' => true],
                    ['option_text' => 'الكتاب', 'is_correct' => false],
                    ['option_text' => 'لا يوجد فاعل', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'أي من الكلمات التالية تعتبر حرفاً؟',
                'options' => [
                    ['option_text' => 'سيارة', 'is_correct' => false],
                    ['option_text' => 'يجري', 'is_correct' => false],
                    ['option_text' => 'في', 'is_correct' => true],
                    ['option_text' => 'جميل', 'is_correct' => false],
                ],
            ],
        ];
        
        foreach ($questions as $questionData) {
            $question = Question::create([
                'question_type_id' => $questionTypeId,
                'level_id' => $levelId,
                'assessment_id' => $assessment->id,
                'question_text' => $questionData['question_text'],
                'points' => 10,
                'is_pretest' => true,
            ]);
            
            foreach ($questionData['options'] as $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
        }
    }
} 