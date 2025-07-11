<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Course;
use App\Models\Question;
use App\Models\Assessment;
use App\Models\FailedQuestion;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PreTestController extends Controller
{
    /**
     * عرض اختبار تحديد المستوى
     */
    public function show(Course $course, Level $level)
    {
        // التحقق من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        // التحقق من أن الطالب مسجل في الدورة
        $student = Auth::user();
        $enrollment = $student->enrolledCourses()->where('course_id', $course->id)->first();
        
        if (!$enrollment) {
            return redirect()->route('courses.catalog')->with('error', 'يجب التسجيل في الدورة أولاً');
        }
        
        // البحث عن تقييم من نوع اختبار تحديد المستوى لهذا المستوى
        $preTest = Assessment::where('level_id', $level->id)
                            ->where('is_pretest', true)
                            ->first();
        
        // تسجيل معلومات عن التقييم
        Log::info('PreTest Info:', [
            'level_id' => $level->id,
            'preTest' => $preTest ? $preTest->toArray() : 'No pretest found'
        ]);
        
        if (!$preTest) {
            return redirect()->route('student.courses.show', $course->id)
                ->with('info', 'لا يوجد اختبار تحديد مستوى لهذا المستوى');
        }
        
        // الحصول على أسئلة الاختبار المرتبطة بهذا التقييم
        $questions = $preTest->questions()
                            ->with(['options', 'content'])
                            ->inRandomOrder()
                            ->take(10)
                            ->get();
        
        // تسجيل معلومات عن الأسئلة
        Log::info('Questions Info:', [
            'assessment_id' => $preTest->id,
            'questions_count' => $questions->count(),
            'questions' => $questions->isEmpty() ? 'No questions found' : $questions->pluck('id', 'question_text')->toArray()
        ]);
                            
        if ($questions->isEmpty()) {
            return redirect()->route('student.courses.show', $course->id)
                ->with('info', 'لا توجد أسئلة متاحة لاختبار تحديد المستوى لهذا المستوى');
        }
        
        return view('levels.pretest', compact('course', 'level', 'questions', 'preTest'));
    }
    
    /**
     * معالجة نتيجة اختبار تحديد المستوى
     */
    public function submit(Request $request, Course $course, Level $level)
    {
        // التحقق من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer'
        ]);
        
        $studentId = Auth::id();
        $answers = $data['answers'];
        $questions = Question::whereIn('id', array_keys($answers))->with(['options', 'content'])->get()->keyBy('id');
        
        // تتبع الإجابات الصحيحة والخاطئة
        $correctAnswers = 0;
        $totalQuestions = count($answers);
        $failedQuestionIds = [];
        $failedQuestionsWithContent = [];
        
        // حذف الأسئلة السابقة التي رسب فيها الطالب في هذا المستوى
        FailedQuestion::where('student_id', $studentId)
            ->where('level_id', $level->id)
            ->delete();
        
        // تقييم الإجابات
        foreach ($answers as $questionId => $answerId) {
            $question = $questions->get($questionId);
            if (!$question) continue;
            
            $correctOption = $question->options->where('is_correct', true)->first();
            
            if ($correctOption && $correctOption->id == $answerId) {
                $correctAnswers++;
            } else {
                // تسجيل السؤال كسؤال رسب فيه الطالب
                $failedQuestionIds[] = $questionId;
                
                // تسجيل السؤال مع المحتوى المرتبط به (إن وجد)
                if ($question->content_id) {
                    $failedQuestionsWithContent[] = [
                        'question_id' => $questionId,
                        'content_id' => $question->content_id,
                        'content_title' => $question->content ? $question->content->title : 'غير معروف'
                    ];
                }
                
                FailedQuestion::create([
                    'student_id' => $studentId,
                    'question_id' => $questionId,
                    'level_id' => $level->id,
                    'resolved' => false
                ]);
            }
        }
        
        // حساب النسبة المئوية للإجابات الصحيحة
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passScore = 70; // نسبة النجاح
        $passed = $score >= $passScore;
        
        // الحصول على المحتوى الأول في المستوى بغض النظر عن النتيجة
        $firstContent = CourseContent::where('level_id', $level->id)
            ->orderBy('order')
            ->first();
        
        // إذا رسب الطالب، قم بتوجيهه إلى المحتوى المناسب
        if (!$passed && !empty($failedQuestionIds)) {
            // الحصول على المحتوى المرتبط بالأسئلة التي رسب فيها الطالب
            $requiredContents = $this->getRequiredContentsForFailedQuestions($failedQuestionIds);
            
            if ($requiredContents->isNotEmpty()) {
                // توجيه الطالب إلى أول محتوى إجباري
                $firstContent = $requiredContents->first();
                
                return redirect()
                    ->route('student.contents.show', [$course->id, $level->id, $firstContent->id])
                    ->with('warning', 'يجب عليك مشاهدة المحتوى التالي لتحسين فهمك للمواضيع التي رسبت فيها')
                    ->with('required_contents', $requiredContents->pluck('id')->toArray())
                    ->with('failed_questions', $failedQuestionsWithContent);
            }
        }
        
        // تسجيل محاولة الاختبار
        $preTest = Assessment::where('level_id', $level->id)
                            ->where('is_pretest', true)
                            ->first();
                            
        if ($preTest) {
            $preTest->studentAttempts()->create([
                'student_id' => $studentId,
                'score' => $score,
                'passed' => $passed,
                'attempt_number' => 1,
                'status' => 'completed',
                'start_time' => now()->subMinutes(10), // تقدير تقريبي
                'end_time' => now(),
            ]);
        }
        
        // عرض النتيجة
        return view('levels.pretest_result', compact('course', 'level', 'score', 'passed', 'correctAnswers', 'totalQuestions', 'firstContent', 'failedQuestionsWithContent'));
    }
    
    /**
     * الحصول على المحتويات المطلوبة للأسئلة التي رسب فيها الطالب
     */
    private function getRequiredContentsForFailedQuestions(array $failedQuestionIds)
    {
        // الحصول على المحتوى المرتبط بالأسئلة التي رسب فيها الطالب
        $requiredContents = CourseContent::whereIn('id', function($query) use ($failedQuestionIds) {
                $query->select('content_id')
                    ->from('questions')
                    ->whereIn('id', $failedQuestionIds)
                    ->whereNotNull('content_id');
            })
            ->orderBy('order')
            ->get();
            
        // طباعة معلومات تصحيح الأخطاء
        \Log::info('Failed Question IDs: ' . implode(', ', $failedQuestionIds));
        \Log::info('Required Contents Count: ' . $requiredContents->count());
        \Log::info('Required Content IDs: ' . $requiredContents->pluck('id')->implode(', '));
            
        // إذا لم يكن هناك محتوى مرتبط بالأسئلة، حاول الحصول على محتوى من نفس المستوى
        if ($requiredContents->isEmpty()) {
            // الحصول على مستويات الأسئلة التي رسب فيها الطالب
            $levelIds = Question::whereIn('id', $failedQuestionIds)->pluck('level_id')->unique();
            
            \Log::info('No direct content links found. Searching by level. Level IDs: ' . $levelIds->implode(', '));
            
            if ($levelIds->isNotEmpty()) {
                $requiredContents = CourseContent::whereIn('level_id', $levelIds)
                    ->where('is_required', true)
                    ->where(function ($query) {
                        $query->where('content_type_id', function ($subquery) {
                            $subquery->select('id')
                                ->from('content_types')
                                ->where('name', 'فيديو')
                                ->orWhere('name', 'video')
                                ->first();
                        })
                        ->orWhereRaw("LOWER(title) LIKE '%video%'")
                        ->orWhere('title', 'LIKE', '%فيديو%');
                    })
                    ->orderBy('order')
                    ->get();
                    
                \Log::info('Found ' . $requiredContents->count() . ' contents from level search');
            }
        }
        
        return $requiredContents;
    }
}
