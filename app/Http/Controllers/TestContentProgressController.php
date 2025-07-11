<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentContentProgress;
use App\Models\CourseContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestContentProgressController extends Controller
{
    public function testProgress(Request $request)
    {
        // التأكد من أن المستخدم مسجل دخول
        if (!Auth::check()) {
            return response()->json(['error' => 'يجب تسجيل الدخول أولاً'], 401);
        }
        
        $studentId = Auth::id();
        $contentId = $request->input('content_id');
        
        if (!$contentId) {
            return response()->json(['error' => 'يجب تحديد معرف المحتوى'], 400);
        }
        
        try {
            // التحقق من وجود المحتوى
            $content = CourseContent::find($contentId);
            if (!$content) {
                return response()->json(['error' => 'المحتوى غير موجود'], 404);
            }
            
            // إنشاء سجل تقدم اختباري
            $progress = StudentContentProgress::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'content_id' => $contentId,
                ],
                [
                    'watched_seconds' => 60,
                    'duration_seconds' => 120,
                    'fully_watched' => true,
                    'watched_at' => now(),
                    'is_required_content' => false,
                ]
            );
            
            // تسجيل معلومات للتشخيص
            Log::info('تم إنشاء سجل تقدم اختباري', [
                'student_id' => $studentId,
                'content_id' => $contentId,
                'progress_id' => $progress->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء سجل تقدم اختباري بنجاح',
                'progress' => $progress
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء سجل تقدم اختباري', [
                'error' => $e->getMessage(),
                'student_id' => $studentId,
                'content_id' => $contentId
            ]);
            
            return response()->json([
                'error' => 'حدث خطأ أثناء إنشاء سجل التقدم',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
