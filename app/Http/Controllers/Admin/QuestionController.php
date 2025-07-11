<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions (filtered by assessment if provided).
     */
    public function index(Request $request)
    {
        $assessmentId = $request->input('assessment_id');

        $questions = Question::with(['assessment.level.course', 'content', 'questionType'])
            ->when($assessmentId, function ($q) use ($assessmentId) {
                $q->where('assessment_id', $assessmentId);
            })
            ->paginate(20);

        $assessment = $assessmentId ? Assessment::with('level.course')->findOrFail($assessmentId) : null;

        return view('admin.questions.index', compact('questions', 'assessment'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Request $request)
    {
        $assessmentId = $request->input('assessment_id');
        $assessment   = Assessment::with('level.contents')->findOrFail($assessmentId);

        $questionTypes = QuestionType::all();
        $contents      = $assessment->level?->contents;

        return view('admin.questions.create', compact('assessment', 'questionTypes', 'contents'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id'     => 'required|exists:assessments,id',
            'question_type_id'  => 'required|exists:question_types,id',
            'question_text'     => 'required|string|max:1000',
            'points'            => 'required|integer|min:1',
            'content_id'        => 'nullable|exists:course_contents,id',
            'explanation'       => 'nullable|string',
        ]);

        $question = Question::create($validated);

        // تسجيل البيانات المستلمة للتصحيح
        Log::info('Question Data:', [
            'question_id' => $question->id,
            'question_type_id' => $validated['question_type_id'],
            'options' => $request->input('options'),
            'correct_option' => $request->input('correct_option'),
            'correct_answer' => $request->input('correct_answer'),
        ]);

        // معالجة الخيارات بناءً على نوع السؤال
        $questionType = QuestionType::find($validated['question_type_id']);
        $typeName = $questionType->name;

        if ($typeName === 'اختيار من متعدد' || $typeName === 'Multiple Choice') {
            $options    = $request->input('options'); // array texts
            $correctIdx = (int) $request->input('correct_option');

            Log::info('Processing Multiple Choice Options:', [
                'options' => $options,
                'correctIdx' => $correctIdx
            ]);

            if (is_array($options) && count($options) >= 2) {
                foreach ($options as $idx => $text) {
                    if (trim($text) === '') continue;
                    $question->options()->create([
                        'option_text' => $text,
                        'is_correct'  => $idx == $correctIdx,
                        'order'       => $idx,
                    ]);
                }
            }
        } elseif ($typeName === 'صح وخطأ' || $typeName === 'True/False') {
            $correct = $request->input('correct_answer') === 'true';
            
            Log::info('Processing True/False Options:', [
                'correct_answer' => $request->input('correct_answer'),
                'is_true_correct' => $correct
            ]);
            
            $question->options()->createMany([
                ['option_text' => 'صح',  'is_correct' => $correct,  'order' => 0],
                ['option_text' => 'خطأ', 'is_correct' => !$correct, 'order' => 1],
            ]);
        }

        return redirect()->route('admin.questions.index', ['assessment_id' => $validated['assessment_id']])
            ->with('success', 'تم إضافة السؤال بنجاح');
    }

    /**
     * Show form for editing question.
     */
    public function edit(Question $question)
    {
        $assessment   = $question->assessment()->with('level.contents')->first();
        $questionTypes = QuestionType::all();
        $contents      = $assessment->level?->contents;

        return view('admin.questions.edit', compact('question', 'assessment', 'questionTypes', 'contents'));
    }

    /**
     * Update question.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_type_id'  => 'required|exists:question_types,id',
            'question_text'     => 'required|string|max:1000',
            'points'            => 'required|integer|min:1',
            'content_id'        => 'nullable|exists:course_contents,id',
            'explanation'       => 'nullable|string',
        ]);

        // تحديث معلومات السؤال الأساسية
        $question->update($validated);

        // تسجيل البيانات المستلمة للتصحيح
        Log::info('Question Update Data:', [
            'question_id' => $question->id,
            'question_type_id' => $validated['question_type_id'],
            'options' => $request->input('options'),
            'correct_option' => $request->input('correct_option'),
            'correct_answer' => $request->input('correct_answer'),
        ]);

        // معالجة الخيارات بناءً على نوع السؤال
        $questionType = QuestionType::find($validated['question_type_id']);
        $typeName = $questionType->name;

        // حذف الخيارات القديمة
        $question->options()->delete();

        if ($typeName === 'اختيار من متعدد' || $typeName === 'Multiple Choice') {
            $options    = $request->input('options'); // array texts
            $correctIdx = (int) $request->input('correct_option');

            Log::info('Processing Multiple Choice Options for Update:', [
                'options' => $options,
                'correctIdx' => $correctIdx
            ]);

            if (is_array($options) && count($options) >= 2) {
                foreach ($options as $idx => $text) {
                    if (trim($text) === '') continue;
                    $question->options()->create([
                        'option_text' => $text,
                        'is_correct'  => $idx == $correctIdx,
                        'order'       => $idx,
                    ]);
                }
            }
        } elseif ($typeName === 'صح وخطأ' || $typeName === 'True/False') {
            $correct = $request->input('correct_answer') === 'true';
            
            Log::info('Processing True/False Options for Update:', [
                'correct_answer' => $request->input('correct_answer'),
                'is_true_correct' => $correct
            ]);
            
            $question->options()->createMany([
                ['option_text' => 'صح',  'is_correct' => $correct,  'order' => 0],
                ['option_text' => 'خطأ', 'is_correct' => !$correct, 'order' => 1],
            ]);
        }

        return redirect()->route('admin.questions.index', ['assessment_id' => $question->assessment_id])
            ->with('success', 'تم تحديث السؤال بنجاح');
    }

    /**
     * Remove question.
     */
    public function destroy(Question $question)
    {
        $assessmentId = $question->assessment_id;
        $question->delete();

        return redirect()->route('admin.questions.index', ['assessment_id' => $assessmentId])
            ->with('success', 'تم حذف السؤال بنجاح');
    }
} 