<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseContent;
use App\Models\ContentType;
use App\Models\Level;
use App\Models\StudentContentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseContentController extends Controller
{
    /**
     * عرض نموذج إنشاء محتوى جديد
     */
    public function create(Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $contentTypes = ContentType::all();
        
        return view('contents.create', compact('course', 'level', 'contentTypes'));
    }

    /**
     * حفظ محتوى جديد
     */
    public function store(Request $request, Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'content_type_id' => 'required|exists:content_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string|max:255',
            'content_file' => 'nullable|file|max:102400',
            'duration' => 'nullable|integer|min:1',
            'order' => 'required|integer|min:0',
            'is_required' => 'boolean',
        ]);
        
        // معالجة الملف المرفق إذا وجد
        if ($request->hasFile('content_file')) {
            $file = $request->file('content_file');
            $path = $file->store('course_contents/' . $course->id . '/' . $level->id, 'public');
            $validated['content_url'] = $path;
        }
        
        $validated['level_id'] = $level->id;
        
        $content = CourseContent::create($validated);
        
        return redirect()->route('courses.levels.show', [$course, $level])
            ->with('success', 'تم إضافة المحتوى بنجاح');
    }

    /**
     * عرض محتوى محدد
     */
    public function show(Course $course, Level $level, CourseContent $content)
    {
        // التأكد من أن المحتوى ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $content->level_id !== $level->id) {
            abort(404);
        }
        
        $content->load('contentType');
        
        return view('contents.show', compact('course', 'level', 'content'));
    }

    /**
     * عرض نموذج تعديل محتوى
     */
    public function edit(Course $course, Level $level, CourseContent $content)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المحتوى ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $content->level_id !== $level->id) {
            abort(404);
        }
        
        $contentTypes = ContentType::all();
        
        return view('contents.edit', compact('course', 'level', 'content', 'contentTypes'));
    }

    /**
     * تحديث محتوى
     */
    public function update(Request $request, Course $course, Level $level, CourseContent $content)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المحتوى ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $content->level_id !== $level->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'content_type_id' => 'required|exists:content_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string|max:255',
            'content_file' => 'nullable|file|max:102400',
            'duration' => 'nullable|integer|min:1',
            'order' => 'required|integer|min:0',
            'is_required' => 'boolean',
        ]);
        
        // معالجة الملف المرفق إذا وجد
        if ($request->hasFile('content_file')) {
            // حذف الملف القديم إذا كان موجودًا
            if ($content->content_url && Storage::disk('public')->exists($content->content_url)) {
                Storage::disk('public')->delete($content->content_url);
            }
            
            $file = $request->file('content_file');
            $path = $file->store('course_contents/' . $course->id . '/' . $level->id, 'public');
            $validated['content_url'] = $path;
        }
        
        $content->update($validated);
        
        return redirect()->route('courses.levels.show', [$course, $level])
            ->with('success', 'تم تحديث المحتوى بنجاح');
    }

    /**
     * حذف محتوى
     */
    public function destroy(Course $course, Level $level, CourseContent $content)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المحتوى ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $content->level_id !== $level->id) {
            abort(404);
        }
        
        // حذف الملف المرتبط إذا وجد
        if ($content->content_url && Storage::disk('public')->exists($content->content_url)) {
            Storage::disk('public')->delete($content->content_url);
        }
        
        $content->delete();
        
        return redirect()->route('courses.levels.show', [$course, $level])
            ->with('success', 'تم حذف المحتوى بنجاح');
    }
    
    /**
     * إعادة ترتيب المحتويات
     */
    public function reorder(Request $request, Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $request->validate([
            'contents' => 'required|array',
            'contents.*' => 'exists:course_contents,id'
        ]);
        
        $contents = $request->input('contents');
        
        foreach ($contents as $order => $id) {
            CourseContent::where('id', $id)->where('level_id', $level->id)->update(['order' => $order]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * عرض المحتوى للطلاب
     */
    public function studentView(Course $course, Level $level, CourseContent $content)
    {
        // التحقق من أن الطالب مسجل في الدورة
        if (!$course->students()->where('student_courses.student_id', auth()->id())->exists()) {
            return redirect()->route('courses.catalog')
                ->with('error', 'يجب التسجيل في الدورة أولاً');
        }
        
        // التأكد من أن المحتوى ينتمي للمستوى والدورة
        if ($level->course_id !== $course->id || $content->level_id !== $level->id) {
            abort(404);
        }
        
        $user = auth()->user();
        
        // متغير لتحديد ما إذا كان المحتوى إجباريًا
        $isRequiredContent = false;
        $passedPreTest = true;
        
        // التحقق من وجود اختبار تحديد مستوى للمستوى وما إذا كان الطالب قد أكمله
        if ($level->hasPreTest()) {
            $hasCompletedPreTest = $level->hasCompletedPreTest(auth()->id());
            $passedPreTest = $level->hasPassedPreTest(auth()->id());
            
            // إذا لم يكمل الطالب اختبار تحديد المستوى، نضيف رسالة تحذير فقط
            if (!$hasCompletedPreTest) {
                session()->flash('warning', 'يفضل إكمال اختبار تحديد المستوى قبل الوصول إلى محتوى هذا المستوى');
            }
            
            // إذا لم يجتز الطالب اختبار تحديد المستوى، نقوم بتحديد المحتوى المرتبط بالأسئلة التي أخطأ فيها كمحتوى إجباري
            if ($hasCompletedPreTest && !$passedPreTest) {
                // الحصول على محتويات مرتبطة بالأسئلة التي رسب فيها الطالب
                $failedQuestionContentIds = \App\Models\Question::whereHas('failedQuestions', function($query) use ($user) {
                    $query->where('student_id', $user->id)
                        ->where('resolved', false)
                        ->where('level_id', $query->getModel()->level_id);
                })
                ->whereNotNull('content_id')
                ->pluck('content_id')
                ->unique();
                
                // تسجيل معلومات التصحيح
                Log::info('User ID: ' . $user->id . ' checking content access for content ID: ' . $content->id);
                Log::info('Failed question content IDs: ' . $failedQuestionContentIds->implode(', '));
                Log::info('Current content is required: ' . ($failedQuestionContentIds->contains($content->id) ? 'Yes' : 'No'));
                
                // التحقق مما إذا كان المحتوى الحالي مرتبط بسؤال فشل فيه الطالب
                $isRequiredContent = $failedQuestionContentIds->contains($content->id);
            }
        }
        
        $content->load('contentType');
        
        // الحصول على معلومات عن تقدم الطالب في هذا المحتوى
        $contentProgress = \App\Models\StudentContentProgress::where('student_id', $user->id)
            ->where('content_id', $content->id)
            ->first();
            
        // التحقق مما إذا كان هذا المحتوى مطلوبًا بسبب سؤال فشل فيه الطالب
        $hasFailedQuestions = \App\Models\FailedQuestion::where('student_id', $user->id)
            ->where('resolved', false)
            ->whereHas('question', function ($q) use ($content) {
                $q->where('content_id', $content->id);
            })
            ->exists();
            
        if ($hasFailedQuestions) {
            $isRequiredContent = true;
        }
        
        // تحديث نسبة التقدم في الدورة
        $this->updateProgress($course);
        
        // إضافة متغير لتحديد ما إذا كان الطالب قد اجتاز اختبار تحديد المستوى
        return view('student.contents.show', compact('course', 'level', 'content', 'contentProgress', 'isRequiredContent', 'passedPreTest'));
    }
    
    /**
     * تحديث نسبة التقدم في الدورة
     */
    private function updateProgress(Course $course)
    {
        $enrollment = $course->students()->where('student_courses.student_id', auth()->id())->first()->pivot;
        
        // حساب إجمالي عدد المحتويات المطلوبة
        $totalRequired = 0;
        $completedRequired = 0;
        
        foreach ($course->levels as $level) {
            foreach ($level->contents as $content) {
                if ($content->is_required) {
                    $totalRequired++;
                    
                    // هنا يمكن إضافة منطق للتحقق من إكمال المحتوى
                    // مثلاً من خلال جدول منفصل يتتبع المحتويات المكتملة
                    // لكن لأغراض التبسيط، نفترض أن المحتوى الذي تم عرضه يعتبر مكتملاً
                    // وهذا يمكن تحسينه لاحقًا
                    
                    // لو كان هناك جدول للمحتويات المكتملة:
                    // if (CompletedContent::where('user_id', auth()->id())->where('content_id', $content->id)->exists()) {
                    //     $completedRequired++;
                    // }
                }
            }
        }
        
        if ($totalRequired > 0) {
            $progress = ($completedRequired / $totalRequired) * 100;
            
            // تحديث نسبة التقدم
            $course->students()->updateExistingPivot(auth()->id(), [
                'progress_percentage' => $progress,
                'status' => $progress >= 100 ? 'completed' : 'in_progress'
            ]);
        }
    }
} 