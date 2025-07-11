<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\Level;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * عرض قائمة الأسئلة لتقييم معين
     */
    public function index(Course $course, Level $level, Assessment $assessment)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        $questions = $assessment->questions()->with(['questionType', 'options'])->get();
        
        return view('questions.index', compact('course', 'level', 'assessment', 'questions'));
    }

    /**
     * عرض نموذج إنشاء سؤال جديد
     */
    public function create(Course $course, Level $level, Assessment $assessment)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        $questionTypes = QuestionType::all();
        
        return view('questions.create', compact('course', 'level', 'assessment', 'questionTypes'));
    }

    /**
     * حفظ سؤال جديد
     */
    public function store(Request $request, Course $course, Level $level, Assessment $assessment)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن التقييم ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $assessment->level_id !== $level->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'question_type_id' => 'required|exists:question_types,id',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'boolean',
            'options.*.feedback' => 'nullable|string',
        ]);
        
        // إنشاء السؤال
        $question = Question::create([
            'assessment_id' => $assessment->id,
            'question_type_id' => $validated['question_type_id'],
            'question_text' => $validated['question_text'],
            'points' => $validated['points'],
            'explanation' => $validated['explanation'],
        ]);
        
        // إضافة الخيارات
        foreach ($validated['options'] as $optionData) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $optionData['text'],
                'is_correct' => isset($optionData['is_correct']) ? $optionData['is_correct'] : false,
                'feedback' => $optionData['feedback'] ?? null,
            ]);
        }
        
        return redirect()->route('courses.levels.assessments.questions.index', [$course, $level, $assessment])
            ->with('success', 'تم إضافة السؤال بنجاح');
    }

    /**
     * عرض سؤال محدد
     */
    public function show(Course $course, Level $level, Assessment $assessment, Question $question)
    {
        $this->authorize('view', $course);
        
        // التأكد من أن السؤال ينتمي للتقييم والمستوى والدورة
        if ($level->course_id !== $course->id || 
            $assessment->level_id !== $level->id || 
            $question->assessment_id !== $assessment->id) {
            abort(404);
        }
        
        $question->load(['questionType', 'options']);
        
        return view('questions.show', compact('course', 'level', 'assessment', 'question'));
    }

    /**
     * عرض نموذج تعديل سؤال
     */
    public function edit(Course $course, Level $level, Assessment $assessment, Question $question)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن السؤال ينتمي للتقييم والمستوى والدورة
        if ($level->course_id !== $course->id || 
            $assessment->level_id !== $level->id || 
            $question->assessment_id !== $assessment->id) {
            abort(404);
        }
        
        $questionTypes = QuestionType::all();
        $question->load('options');
        
        return view('questions.edit', compact('course', 'level', 'assessment', 'question', 'questionTypes'));
    }

    /**
     * تحديث سؤال
     */
    public function update(Request $request, Course $course, Level $level, Assessment $assessment, Question $question)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن السؤال ينتمي للتقييم والمستوى والدورة
        if ($level->course_id !== $course->id || 
            $assessment->level_id !== $level->id || 
            $question->assessment_id !== $assessment->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'question_type_id' => 'required|exists:question_types,id',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*.id' => 'nullable|exists:question_options,id',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'boolean',
            'options.*.feedback' => 'nullable|string',
        ]);
        
        // تحديث السؤال
        $question->update([
            'question_type_id' => $validated['question_type_id'],
            'question_text' => $validated['question_text'],
            'points' => $validated['points'],
            'explanation' => $validated['explanation'],
        ]);
        
        // الحصول على معرفات الخيارات الحالية
        $existingOptionIds = $question->options->pluck('id')->toArray();
        $updatedOptionIds = [];
        
        // تحديث الخيارات الموجودة وإنشاء خيارات جديدة
        foreach ($validated['options'] as $optionData) {
            if (isset($optionData['id'])) {
                // تحديث خيار موجود
                $option = QuestionOption::find($optionData['id']);
                if ($option && $option->question_id === $question->id) {
                    $option->update([
                        'option_text' => $optionData['text'],
                        'is_correct' => isset($optionData['is_correct']) ? $optionData['is_correct'] : false,
                        'feedback' => $optionData['feedback'] ?? null,
                    ]);
                    $updatedOptionIds[] = $option->id;
                }
            } else {
                // إنشاء خيار جديد
                $option = QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['text'],
                    'is_correct' => isset($optionData['is_correct']) ? $optionData['is_correct'] : false,
                    'feedback' => $optionData['feedback'] ?? null,
                ]);
                $updatedOptionIds[] = $option->id;
            }
        }
        
        // حذف الخيارات التي لم تعد موجودة
        $optionsToDelete = array_diff($existingOptionIds, $updatedOptionIds);
        QuestionOption::whereIn('id', $optionsToDelete)->delete();
        
        return redirect()->route('courses.levels.assessments.questions.index', [$course, $level, $assessment])
            ->with('success', 'تم تحديث السؤال بنجاح');
    }

    /**
     * حذف سؤال
     */
    public function destroy(Course $course, Level $level, Assessment $assessment, Question $question)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن السؤال ينتمي للتقييم والمستوى والدورة
        if ($level->course_id !== $course->id || 
            $assessment->level_id !== $level->id || 
            $question->assessment_id !== $assessment->id) {
            abort(404);
        }
        
        $question->delete();
        
        return redirect()->route('courses.levels.assessments.questions.index', [$course, $level, $assessment])
            ->with('success', 'تم حذف السؤال بنجاح');
    }
} 