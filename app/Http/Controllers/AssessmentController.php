<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\Course;
use App\Models\Level;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    /**
     * عرض قائمة التقييمات لمستوى معين
     */
    public function index(Course $course, Level $level)
    {
        $this->authorize('view', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $assessments = $level->assessments()->with('assessmentType')->get();
        
        return view('assessments.index', compact('course', 'level', 'assessments'));
    }

    /**
     * عرض نموذج إنشاء تقييم جديد
     */
    public function create(Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $assessmentTypes = AssessmentType::all();
        
        return view('assessments.create', compact('course', 'level', 'assessmentTypes'));
    }

    /**
     * حفظ تقييم جديد
     */
    public function store(Request $request, Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'status' => 'required|in:draft,active,inactive',
        ]);
        
        $validated['level_id'] = $level->id;
        
        $assessment = Assessment::create($validated);
        
        return redirect()->route('courses.levels.assessments.questions.index', [$course, $level, $assessment])
            ->with('success', 'تم إنشاء التقييم بنجاح. يرجى إضافة أسئلة للتقييم.');
    }

    /**
     * عرض تقييم محدد
     */
    public function show(Course $course, Level $level, Assessment $assessment)
    {
        $this->authorize('view', $course);
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        $assessment->load(['assessmentType', 'questions.options']);
        
        return view('assessments.show', compact('course', 'level', 'assessment'));
    }

    /**
     * عرض نموذج تعديل تقييم
     */
    public function edit(Course $course, Level $level, Assessment $assessment)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        $assessmentTypes = AssessmentType::all();
        
        return view('assessments.edit', compact('course', 'level', 'assessment', 'assessmentTypes'));
    }

    /**
     * تحديث تقييم
     */
    public function update(Request $request, Course $course, Level $level, Assessment $assessment)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'status' => 'required|in:draft,active,inactive',
        ]);
        
        $assessment->update($validated);
        
        return redirect()->route('courses.levels.assessments.show', [$course, $level, $assessment])
            ->with('success', 'تم تحديث التقييم بنجاح');
    }

    /**
     * حذف تقييم
     */
    public function destroy(Course $course, Level $level, Assessment $assessment)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        $assessment->delete();
        
        return redirect()->route('courses.levels.assessments.index', [$course, $level])
            ->with('success', 'تم حذف التقييم بنجاح');
    }
    
    /**
     * عرض التقييم للطلاب
     */
    public function takeAssessment(Course $course, Level $level, Assessment $assessment)
    {
        // التحقق من أن الطالب مسجل في الدورة
        if (!$course->students()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('courses.catalog')
                ->with('error', 'يجب التسجيل في الدورة أولاً');
        }
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        // التحقق من عدد المحاولات المتاحة
        $attemptCount = StudentAssessment::where('student_id', Auth::id())
            ->where('assessment_id', $assessment->id)
            ->count();
            
        if ($attemptCount >= $assessment->attempts_allowed) {
            return redirect()->route('student.courses.levels.show', [$course, $level])
                ->with('error', 'لقد استنفدت العدد المسموح به من المحاولات لهذا التقييم');
        }
        
        // إنشاء محاولة جديدة
        $attempt = StudentAssessment::create([
            'student_id' => Auth::id(),
            'assessment_id' => $assessment->id,
            'attempt_number' => $attemptCount + 1,
            'start_time' => now(),
        ]);
        
        // الحصول على الأسئلة (مع ترتيب عشوائي إذا كان مطلوبًا)
        $questions = $assessment->questions();
        
        if ($assessment->randomize_questions) {
            $questions = $questions->inRandomOrder();
        }
        
        $questions = $questions->with('options')->get();
        
        return view('student.assessments.take', compact('course', 'level', 'assessment', 'questions', 'attempt'));
    }
    
    /**
     * تقديم إجابات التقييم
     */
    public function submitAssessment(Request $request, Course $course, Level $level, Assessment $assessment, StudentAssessment $attempt)
    {
        // التحقق من أن الطالب مسجل في الدورة
        if (!$course->students()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('courses.catalog')
                ->with('error', 'يجب التسجيل في الدورة أولاً');
        }
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        // التأكد من أن المحاولة تخص الطالب الحالي والتقييم المحدد
        if ($attempt->student_id !== Auth::id() || $attempt->assessment_id !== $assessment->id) {
            abort(403);
        }
        
        // التحقق من أن المحاولة لم تنتهِ بعد
        if ($attempt->end_time !== null) {
            return redirect()->route('student.courses.levels.show', [$course, $level])
                ->with('error', 'لقد تم تقديم هذه المحاولة بالفعل');
        }
        
        // الحصول على الأسئلة
        $questions = $assessment->questions()->with('options')->get();
        
        // حساب الدرجة
        $totalPoints = $questions->sum('points');
        $earnedPoints = 0;
        
        foreach ($questions as $question) {
            $answerId = $request->input('question_' . $question->id);
            
            if ($answerId) {
                $option = QuestionOption::find($answerId);
                
                if ($option && $option->question_id === $question->id && $option->is_correct) {
                    $earnedPoints += $question->points;
                }
            }
        }
        
        // حساب النسبة المئوية
        $scorePercentage = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $passed = $scorePercentage >= $assessment->passing_score;
        
        // تحديث المحاولة
        $attempt->update([
            'score' => $scorePercentage,
            'passed' => $passed,
            'end_time' => now(),
        ]);
        
        return redirect()->route('student.assessments.results', [$course, $level, $assessment, $attempt])
            ->with('success', 'تم تقديم التقييم بنجاح');
    }
    
    /**
     * عرض نتائج التقييم
     */
    public function showResults(Course $course, Level $level, Assessment $assessment, StudentAssessment $attempt)
    {
        // التحقق من أن الطالب مسجل في الدورة
        if (!$course->students()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('courses.catalog')
                ->with('error', 'يجب التسجيل في الدورة أولاً');
        }
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        // التأكد من أن المحاولة تخص الطالب الحالي والتقييم المحدد
        if ($attempt->student_id !== Auth::id() || $attempt->assessment_id !== $assessment->id) {
            abort(403);
        }
        
        return view('student.assessments.results', compact('course', 'level', 'assessment', 'attempt'));
    }
} 