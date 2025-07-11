<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\Level;
use App\Models\Course;

class Course4PretestQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على نوع سؤال اختيار من متعدد
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
        
        // الحصول على مستويات الدورة رقم 4
        $course = Course::find(4);
        if (!$course) {
            $this->command->info('لا توجد دورة برقم 4');
            return;
        }
        
        $levels = $course->levels;
        if ($levels->isEmpty()) {
            $this->command->info('لا توجد مستويات للدورة رقم 4');
            return;
        }
        
        // إنشاء أو الحصول على نوع تقييم لاختبار تحديد المستوى
        $assessmentType = AssessmentType::where('name', 'اختبار تحديد المستوى')
            ->orWhere('name', 'pre-test')
            ->first();
            
        if (!$assessmentType) {
            $assessmentType = AssessmentType::create([
                'name' => 'اختبار تحديد المستوى',
                'description' => 'اختبار لتحديد مستوى الطالب قبل بدء المحتوى'
            ]);
        }
        
        foreach ($levels as $level) {
            // إنشاء أو الحصول على تقييم لهذا المستوى
            $assessment = Assessment::where('level_id', $level->id)
                ->where('is_pretest', true)
                ->first();
                
            if (!$assessment) {
                $assessment = Assessment::create([
                    'level_id' => $level->id,
                    'assessment_type_id' => $assessmentType->id,
                    'title' => 'اختبار تحديد المستوى - ' . $level->title,
                    'description' => 'اختبار لتحديد مستوى الطالب قبل بدء المحتوى',
                    'passing_score' => 70,
                    'time_limit' => 30,
                    'is_active' => true,
                    'is_pretest' => true,
                ]);
            }
            
            // إنشاء أسئلة للاختبار
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
                    'question_text' => 'ما هي عاصمة المملكة العربية السعودية؟',
                    'options' => [
                        ['option_text' => 'جدة', 'is_correct' => false],
                        ['option_text' => 'مكة المكرمة', 'is_correct' => false],
                        ['option_text' => 'الرياض', 'is_correct' => true],
                        ['option_text' => 'المدينة المنورة', 'is_correct' => false],
                    ],
                ],
                [
                    'question_text' => 'من هو مؤلف كتاب "مقدمة ابن خلدون"؟',
                    'options' => [
                        ['option_text' => 'ابن سينا', 'is_correct' => false],
                        ['option_text' => 'ابن خلدون', 'is_correct' => true],
                        ['option_text' => 'ابن رشد', 'is_correct' => false],
                        ['option_text' => 'الفارابي', 'is_correct' => false],
                    ],
                ],
            ];
            
            foreach ($questions as $questionData) {
                // التحقق من وجود السؤال
                $existingQuestion = Question::where('assessment_id', $assessment->id)
                    ->where('question_text', $questionData['question_text'])
                    ->first();
                    
                if (!$existingQuestion) {
                    $question = Question::create([
                        'assessment_id' => $assessment->id,
                        'question_type_id' => $questionTypeId,
                        'question_text' => $questionData['question_text'],
                        'points' => 20, // كل سؤال له 20 نقطة
                        'level_id' => $level->id,
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
            
            $this->command->info('تم إنشاء اختبار تحديد المستوى وأسئلة للمستوى: ' . $level->title);
        }
    }
}
