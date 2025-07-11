<?php

namespace App\Http\Controllers;

use App\Models\CourseContent;
use App\Models\FailedQuestion;
use App\Models\Level;
use App\Models\StudentContentProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContentProgressController extends Controller
{
    /**
     * Update or create progress for a given content.
     */
    public function store(Request $request, CourseContent $content)
    {
        try {
            $data = $request->validate([
                'watched_seconds' => 'required|integer|min:0',
                'duration_seconds' => 'required|integer|min:0',
                'fully_watched' => 'required|boolean',
            ]);

            $studentId = Auth::id();
            
            // الحصول على معلومات المستوى
            $level = Level::findOrFail($content->level_id);
            
            // تحديد ما إذا كان الطالب قد اجتاز اختبار تحديد المستوى
            $passedPreTest = $level->hasPreTest() ? $level->hasPassedPreTest($studentId) : true;
            
            // تحقق مما إذا كان هذا محتوى إجباري للطالب بسبب رسوبه في سؤال
            $isRequiredContent = FailedQuestion::where('student_id', $studentId)
                ->whereHas('question', function ($q) use ($content) {
                    $q->where('content_id', $content->id);
                })
                ->exists();
            
            // تحقق من وجود سجل تقدم سابق
            $existingProgress = StudentContentProgress::where('student_id', $studentId)
                ->where('content_id', $content->id)
                ->first();
                
            // إذا كان المحتوى مكتملًا بالفعل، لا نقوم بتحديث حالة "مشاهدة كاملة"
            // إلا إذا تم تمرير fully_watched=true بشكل صريح
            $fullyWatched = $data['fully_watched'];
            if ($existingProgress && $existingProgress->fully_watched && !$fullyWatched) {
                $fullyWatched = true;
            }
                
            $progress = StudentContentProgress::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'content_id' => $content->id,
                ],
                [
                    'watched_seconds' => $data['watched_seconds'],
                    'duration_seconds' => $data['duration_seconds'],
                    'fully_watched' => $fullyWatched,
                    'watched_at' => $fullyWatched ? Carbon::now() : ($existingProgress && $existingProgress->watched_at ? $existingProgress->watched_at : null),
                    'is_required_content' => $isRequiredContent, // تخزين ما إذا كان هذا محتوى إجباري
                ]
            );

            $resolvedQuestions = 0;
            $allRequiredContentCompleted = false;
            
            // If fully watched, mark related failed question as resolved
            if ($fullyWatched) {
                try {
                    // تحديث حالة الأسئلة التي رسب فيها الطالب والمرتبطة بهذا المحتوى
                    $resolvedQuestions = FailedQuestion::where('student_id', $studentId)
                        ->whereHas('question', function ($q) use ($content) {
                            $q->where('content_id', $content->id);
                        })
                        ->update(['resolved' => true, 'resolved_at' => Carbon::now()]);
                        
                    // تحقق مما إذا تم تحديث أي سؤال
                    if ($resolvedQuestions > 0) {
                        // إعادة تعيين التخزين المؤقت للمتصفح
                        header('Cache-Control: no-cache, no-store, must-revalidate');
                        header('Pragma: no-cache');
                        header('Expires: 0');
                        
                        // تحديث حالة المحتوى الإجباري
                        $progress->is_required_content = false;
                        $progress->save();
                    }
                } catch (\Exception $e) {
                    Log::error('خطأ في تحديث الأسئلة: ' . $e->getMessage());
                }
                    
                try {
                    // Update course progress
                    $this->updateCourseProgress($content, $studentId);
                } catch (\Exception $e) {
                    Log::error('خطأ في تحديث تقدم الدورة: ' . $e->getMessage());
                }
                
                try {
                    // تحقق مما إذا كان الطالب قد أكمل جميع المحتويات الإجبارية للمستوى
                    $allRequiredContentCompleted = $this->checkAllRequiredContentCompleted($content->level_id, $studentId);
                } catch (\Exception $e) {
                    Log::error('خطأ في التحقق من إكمال المحتويات الإجبارية: ' . $e->getMessage());
                    $allRequiredContentCompleted = true; // نفترض أنها مكتملة في حالة الخطأ
                }
                
                // إضافة إشعار تحفيزي إذا كان هذا محتوى إجباري
                if ($isRequiredContent) {
                    // إضافة رسالة تحفيزية إلى جلسة الطلب
                    $message = "لقد أكملت مشاهدة محتوى إجباري بنجاح: {$content->title}";
                    
                    if ($resolvedQuestions > 0) {
                        $message .= ". تم حل {$resolvedQuestions} من الأسئلة التي رسبت فيها سابقاً.";
                    }
                    
                    session()->flash('required_content_completed', $message);
                    
                    // إذا كان الطالب قد أكمل جميع المحتويات الإجبارية
                    if ($allRequiredContentCompleted) {
                        session()->flash('required_content_completed', "تهانينا! لقد أكملت جميع المحتويات الإجبارية المطلوبة في المستوى: {$level->title}");
                    }
                }
            } else {
                try {
                    // حتى إذا لم تكن المشاهدة مكتملة، تحقق من وجود أسئلة فاشلة مرتبطة بهذا المحتوى
                    // وقم بتحديث حالة الأسئلة إلى "محلولة" عند بدء المشاهدة
                    // هذا سيسمح للطالب بالوصول إلى المحتوى بعد الإجابة الخاطئة
                    $failedQuestions = FailedQuestion::where('student_id', $studentId)
                        ->whereHas('question', function ($q) use ($content) {
                            $q->where('content_id', $content->id);
                        })
                        ->where('resolved', false)
                        ->get();
                        
                    // إذا كان هناك أسئلة فاشلة، قم بتحديثها إلى "محلولة جزئياً"
                    // هذا سيسمح للطالب بالوصول إلى المحتوى
                    if ($failedQuestions->isNotEmpty()) {
                        // تسجيل بدء المشاهدة
                        Log::info("الطالب {$studentId} بدأ مشاهدة المحتوى {$content->id} المرتبط بأسئلة فاشلة");
                        
                        // تحديث حالة المحتوى لتكون "قيد المشاهدة"
                        $progress->is_required_content = true;
                        $progress->save();
                        
                        // إذا اجتاز الطالب اختبار تحديد المستوى، نقوم بتحديث حالة الأسئلة الفاشلة إلى محلولة تلقائيًا
                        if ($passedPreTest) {
                            foreach ($failedQuestions as $failedQuestion) {
                                $failedQuestion->resolved = true;
                                $failedQuestion->resolved_at = Carbon::now();
                                $failedQuestion->save();
                                $resolvedQuestions++;
                            }
                            
                            Log::info("تم حل {$resolvedQuestions} أسئلة تلقائيًا لأن الطالب اجتاز اختبار تحديد المستوى");
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('خطأ في معالجة الأسئلة الفاشلة: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'fully_watched' => $fullyWatched,
                'is_required_content' => $isRequiredContent,
                'passed_pretest' => $passedPreTest,
                'level_completed' => $fullyWatched ? $this->isLevelCompleted($content->level_id, $studentId) : false,
                'all_required_completed' => $fullyWatched && isset($allRequiredContentCompleted) ? $allRequiredContentCompleted : false,
                'resolved_questions' => $fullyWatched || $passedPreTest ? $resolvedQuestions : 0,
                'progress' => [
                    'id' => $progress->id,
                    'watched_seconds' => $progress->watched_seconds,
                    'duration_seconds' => $progress->duration_seconds,
                    'watched_percentage' => $progress->duration_seconds > 0 ? round(($progress->watched_seconds / $progress->duration_seconds) * 100) : 0,
                    'fully_watched' => (bool)$progress->fully_watched,
                    'watched_at' => $progress->watched_at ? $progress->watched_at->format('Y-m-d H:i:s') : null,
                ]
            ]);
        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('خطأ في دالة store: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // إعادة استجابة خطأ مع تفاصيل
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء تسجيل تقدم المشاهدة',
                'message' => config('app.debug') ? $e->getMessage() : 'حدث خطأ في النظام، يرجى المحاولة مرة أخرى لاحقاً'
            ], 500);
        }
    }
    
    /**
     * تحديث نسبة التقدم في الدورة
     */
    private function updateCourseProgress(CourseContent $content, int $studentId)
    {
        try {
            $course = $content->level->course;
            
            // حساب عدد المحتويات في الدورة
            $totalContents = CourseContent::whereHas('level', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })->count();
            
            // حساب عدد المحتويات التي شاهدها الطالب بالكامل
            $watchedContents = StudentContentProgress::where('student_id', $studentId)
                ->whereHas('content', function($query) use ($course) {
                    $query->whereHas('level', function($q) use ($course) {
                        $q->where('course_id', $course->id);
                    });
                })
                ->where('fully_watched', true)
                ->count();
            
            // حساب نسبة التقدم
            $progress = $totalContents > 0 ? ($watchedContents / $totalContents) * 100 : 0;
            
            // تحديث نسبة التقدم في جدول student_courses
            $studentCourse = $course->students()->where('student_id', $studentId)->first();
            if ($studentCourse) {
                $studentCourse->pivot->progress = $progress;
                $studentCourse->pivot->save();
            }
        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('خطأ في دالة updateCourseProgress: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
    
    /**
     * التحقق مما إذا كان الطالب قد أكمل جميع المحتويات الإجبارية للمستوى
     */
    private function checkAllRequiredContentCompleted(int $levelId, int $studentId): bool
    {
        try {
            // الحصول على جميع المحتويات المرتبطة بالأسئلة التي رسب فيها الطالب في هذا المستوى
            $failedQuestions = FailedQuestion::where('student_id', $studentId)
                ->where('level_id', $levelId)
                ->where('resolved', false)
                ->with('question')
                ->get();
                
            // استخراج معرفات المحتويات من الأسئلة، مع التحقق من وجود علاقة question وحقل content_id
            $requiredContentIds = collect();
            foreach ($failedQuestions as $failedQuestion) {
                if ($failedQuestion->question && $failedQuestion->question->content_id) {
                    $requiredContentIds->push($failedQuestion->question->content_id);
                }
            }
            $requiredContentIds = $requiredContentIds->unique();
            
            // إذا لم تكن هناك محتويات إجبارية، فقد تم إكمالها بالفعل
            if ($requiredContentIds->isEmpty()) {
                return true;
            }
            
            // عدد المحتويات الإجبارية التي تم إكمالها
            $completedCount = StudentContentProgress::where('student_id', $studentId)
                ->whereIn('content_id', $requiredContentIds)
                ->where('fully_watched', true)
                ->count();
                
            return $completedCount === $requiredContentIds->count();
        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('خطأ في دالة checkAllRequiredContentCompleted: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // في حالة حدوث خطأ، نفترض أن المحتويات مكتملة لتجنب منع الطالب من المتابعة
            return true;
        }
    }
    
    /**
     * التحقق مما إذا كان الطالب قد أكمل جميع محتويات المستوى
     */
    private function isLevelCompleted(int $levelId, int $studentId): bool
    {
        try {
            // الحصول على عدد المحتويات في المستوى
            $totalContents = CourseContent::where('level_id', $levelId)->count();
            
            // الحصول على عدد المحتويات التي شاهدها الطالب بالكامل
            $watchedContents = StudentContentProgress::where('student_id', $studentId)
                ->whereHas('content', function($query) use ($levelId) {
                    $query->where('level_id', $levelId);
                })
                ->where('fully_watched', true)
                ->count();
            
            return $totalContents > 0 && $watchedContents === $totalContents;
        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('خطأ في دالة isLevelCompleted: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // في حالة حدوث خطأ، نفترض أن المستوى غير مكتمل
            return false;
        }
    }
    
    /**
     * الحصول على قائمة المحتويات الإجبارية للطالب بناءً على الأسئلة التي رسب فيها
     */
    public function getRequiredContents($studentId, $levelId)
    {
        try {
            // الحصول على الأسئلة التي رسب فيها الطالب في هذا المستوى
            $failedQuestions = FailedQuestion::where('student_id', $studentId)
                ->where('level_id', $levelId)
                ->where('resolved', false)
                ->with('question.content')
                ->get();
                
            // استخراج المحتويات من الأسئلة، مع التحقق من وجود علاقة question وحقل content
            $requiredContents = collect();
            foreach ($failedQuestions as $failedQuestion) {
                if ($failedQuestion->question && $failedQuestion->question->content) {
                    $requiredContents->push($failedQuestion->question->content);
                }
            }
            $requiredContents = $requiredContents->unique('id');
            
            // إذا لم تكن هناك محتويات، نعيد مجموعة فارغة
            if ($requiredContents->isEmpty()) {
                return $requiredContents;
            }
            
            // الحصول على حالة التقدم لكل محتوى
            $contentProgress = StudentContentProgress::where('student_id', $studentId)
                ->whereIn('content_id', $requiredContents->pluck('id'))
                ->get()
                ->keyBy('content_id');
                
            $result = $requiredContents->map(function ($content) use ($contentProgress) {
                $progress = $contentProgress->get($content->id);
                return [
                    'content' => $content,
                    'progress' => $progress,
                    'is_completed' => $progress ? $progress->fully_watched : false
                ];
            });
            
            return $result;
        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('خطأ في دالة getRequiredContents: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // في حالة حدوث خطأ، نعيد مجموعة فارغة
            return collect();
        }
    }
    
    /**
     * اختبار تسجيل تقدم المحتوى - للمساعدة في تشخيص المشكلات
     */
    public function testProgress(Request $request)
    {
        $request->validate([
            'content_id' => 'required|exists:course_contents,id',
        ]);
        
        $contentId = $request->content_id;
        $studentId = Auth::id();
        $content = CourseContent::findOrFail($contentId);
        
        // تحقق من وجود سجل تقدم سابق
        $existingProgress = StudentContentProgress::where('student_id', $studentId)
            ->where('content_id', $contentId)
            ->first();
            
        // تحقق من وجود أسئلة مرتبطة بهذا المحتوى
        $failedQuestions = FailedQuestion::where('student_id', $studentId)
            ->whereHas('question', function ($q) use ($contentId) {
                $q->where('content_id', $contentId);
            })
            ->get();
            
        if ($existingProgress) {
            // إذا كان هناك سجل موجود، قم بإعادة تعيين حالة المشاهدة الكاملة
            $existingProgress->fully_watched = true;
            $existingProgress->watched_seconds = $existingProgress->duration_seconds;
            $existingProgress->watched_at = Carbon::now();
            $existingProgress->save();
            
            // تحديث حالة الأسئلة المرتبطة
            foreach ($failedQuestions as $failedQuestion) {
                $failedQuestion->resolved = true;
                $failedQuestion->resolved_at = Carbon::now();
                $failedQuestion->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث سجل التقدم الموجود بنجاح',
                'progress' => $existingProgress,
                'action' => 'updated',
                'failed_questions_resolved' => $failedQuestions->count()
            ]);
        } else {
            // إنشاء سجل تقدم جديد
            $progress = new StudentContentProgress();
            $progress->student_id = $studentId;
            $progress->content_id = $contentId;
            $progress->watched_seconds = 100; // قيمة افتراضية
            $progress->duration_seconds = 100; // قيمة افتراضية
            $progress->fully_watched = true;
            $progress->watched_at = Carbon::now();
            
            // التحقق مما إذا كان هذا محتوى إجباري
            $isRequiredContent = $failedQuestions->count() > 0;
            $progress->is_required_content = $isRequiredContent;
            $progress->save();
            
            // تحديث حالة الأسئلة المرتبطة
            foreach ($failedQuestions as $failedQuestion) {
                $failedQuestion->resolved = true;
                $failedQuestion->resolved_at = Carbon::now();
                $failedQuestion->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء سجل تقدم جديد بنجاح',
                'progress' => $progress,
                'action' => 'created',
                'failed_questions_resolved' => $failedQuestions->count(),
                'is_required_content' => $isRequiredContent
            ]);
        }
    }
}
