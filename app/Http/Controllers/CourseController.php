<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * عرض قائمة الدورات التعليمية
     */
    public function index()
    {
        $courses = Course::with('category', 'creator')->paginate(10);
        return view('courses.index', compact('courses'));
    }

    /**
     * عرض نموذج إنشاء دورة جديدة
     */
    public function create()
    {
        $categories = CourseCategory::all();
        return view('courses.create', compact('categories'));
    }

    /**
     * حفظ دورة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:course_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,active,inactive,archived',
            'visibility' => 'required|in:public,private,password_protected',
            'access_password' => 'nullable|required_if:visibility,password_protected|string|min:6',
            'featured' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $validated['created_by'] = Auth::id();

        $course = Course::create($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'تم إنشاء الدورة بنجاح');
    }

    /**
     * عرض دورة تعليمية محددة
     */
    public function show(Course $course)
    {
        // تحمّيل العلاقات وحساب عدد المحتويات لكل مستوى
        $course->load(['category', 'creator', 'levels' => function ($query) {
            $query->with('contents')
                  ->withCount('contents')
                  ->orderBy('order');
        }]);

        // هل المستخدم الحالي مسجّل في الدورة؟
        $user       = Auth::user();
        $isEnrolled = false;
        if ($user) {
            $isEnrolled = $course->students()->wherePivot('student_id', $user->id)->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }

    /**
     * عرض نموذج تعديل دورة
     */
    public function edit(Course $course)
    {
        $categories = CourseCategory::all();
        return view('courses.edit', compact('course', 'categories'));
    }

    /**
     * تحديث دورة تعليمية
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:course_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,active,inactive,archived',
            'visibility' => 'required|in:public,private,password_protected',
            'access_password' => 'nullable|required_if:visibility,password_protected|string|min:6',
            'featured' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // حذف الصورة القديمة إذا وجدت
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            $path = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'تم تحديث الدورة بنجاح');
    }

    /**
     * حذف دورة تعليمية
     */
    public function destroy(Course $course)
    {
        // حذف الصورة المصغرة إذا وجدت
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'تم حذف الدورة بنجاح');
    }

    /**
     * عرض الدورات المتاحة للطلاب
     */
    public function catalog()
    {
        $courses = Course::where('status', 'active')
            ->where(function ($query) {
                $query->where('visibility', 'public')
                    ->orWhere(function ($q) {
                        $q->where('visibility', 'private')
                            ->whereHas('students', function ($sq) {
                                $sq->where('student_id', Auth::id());
                            });
                    });
            })
            ->with('category')
            ->paginate(12);
            
        return view('courses.catalog', compact('courses'));
    }

    /**
     * تسجيل الطالب في دورة
     */
    public function enroll(Course $course)
    {
        // إذا كانت الدورة مدفوعة، حوّل إلى مسار الدفع أولاً
        if ($course->price > 0) {
            return redirect()->route('checkout', $course->id);
        }
        
        $user = Auth::user();
        
        // التحقق من عدم التسجيل المسبق
        if ($course->students()->wherePivot('student_id', $user->id)->exists()) {
            return redirect()->route('courses.show', $course)
                ->with('info', 'أنت مسجل بالفعل في هذه الدورة');
        }
        
        // التسجيل في الدورة
        $course->students()->attach($user->id, [
            'enrollment_date' => now(),
            'status' => 'enrolled',
            'progress_percentage' => 0
        ]);
        
        return redirect()->route('student.courses.show', $course)
            ->with('success', 'تم تسجيلك في الدورة بنجاح');
    }

    /**
     * عرض لوحة الدورة للطالب بعد التسجيل
     */
    public function studentShow(Course $course)
    {
        $user = Auth::user();

        // تأكد من أن المستخدم مسجل في الدورة
        $isEnrolled = $course->students()->wherePivot('student_id', $user->id)->exists();
        if (! $isEnrolled) {
            return redirect()->route('courses.show', $course)
                ->with('warning', 'يرجى التسجيل في الدورة أولاً.');
        }

        // جلب المستويات مع المحتوى والتقييمات
        $levels = $course->levels()->with([
            'contents' => function($query) {
                $query->orderBy('order');
            },
            'assessments'
        ])->orderBy('order')->get();

        // منع التخزين المؤقت للصفحة إذا كانت هناك معلمة refresh
        if (request()->has('refresh')) {
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        // جلب جميع الأسئلة المتعلقة بالطالب في هذه المستويات (محلولة وغير محلولة)
        $allQuestions = \App\Models\FailedQuestion::where('student_id', $user->id)
            ->whereIn('level_id', $levels->pluck('id'))
            ->with('question') // جلب العلاقة مع السؤال للحصول على content_id
            ->get();
            
        // تقسيم الأسئلة حسب المستوى
        $failedQuestions = $allQuestions
            ->where('resolved', false)
            ->groupBy('level_id');
            
        // تحديد المستويات التي تم اختبارها
        $testedLevels = $allQuestions->pluck('level_id')->unique();

        // جلب تقدّم المحتوى
        $progress = \App\Models\StudentContentProgress::where('student_id', $user->id)
            ->whereIn('content_id', $levels->flatMap(function ($lvl) { return $lvl->contents->pluck('id'); }))
            ->get()
            ->keyBy('content_id');

        return view('student.courses.dashboard', compact('course', 'levels', 'failedQuestions', 'progress', 'testedLevels'));
    }

    /**
     * صفحة اكتمال الدورة وتحميل الشهادة
     */
    public function completion(Course $course)
    {
        $user = Auth::user();

        // تحقق من التسجيل
        $enrollment = $course->students()->wherePivot('student_id', $user->id)->first();
        if (!$enrollment) {
            return redirect()->route('courses.show', $course)
                ->with('warning', 'يجب التسجيل في الدورة أولاً');
        }

        if ($enrollment->pivot->status !== 'completed') {
            return redirect()->route('student.courses.show', $course)
                ->with('info', 'لم تُكمل جميع متطلبات الدورة بعد');
        }

        // اجلب الشهادة إن وُجدت
        $certificate = \App\Models\Certificate::where('course_id', $course->id)
            ->where('student_id', $user->id)
            ->first();

        return view('student.courses.completion', compact('course', 'certificate'));
    }
} 