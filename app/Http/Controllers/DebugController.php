<?php

namespace App\Http\Controllers;

use App\Models\FailedQuestion;
use App\Models\Question;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    /**
     * عرض معلومات تصحيح الأخطاء للأسئلة التي فشل فيها الطالب
     */
    public function showFailedQuestions()
    {
        $studentId = Auth::id();
        
        // الحصول على الأسئلة التي فشل فيها الطالب
        $failedQuestions = FailedQuestion::where('student_id', $studentId)
            ->where('resolved', false)
            ->with(['question.content', 'level'])
            ->get();
            
        // الحصول على محتويات مرتبطة بالأسئلة التي رسب فيها الطالب
        $failedQuestionContentIds = Question::whereHas('failedQuestions', function($query) use ($studentId) {
            $query->where('student_id', $studentId)
                ->where('resolved', false);
        })
        ->whereNotNull('content_id')
        ->pluck('content_id')
        ->unique();
        
        $requiredContents = CourseContent::whereIn('id', $failedQuestionContentIds)->get();
        
        return view('debug.failed_questions', compact('failedQuestions', 'requiredContents'));
    }
} 