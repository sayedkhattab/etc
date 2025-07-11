<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    /**
     * عرض قائمة المستويات لدورة معينة
     */
    public function index(Course $course)
    {
        $this->authorize('view', $course);
        
        $levels = $course->levels()->orderBy('order')->get();
        
        return view('levels.index', compact('course', 'levels'));
    }

    /**
     * عرض نموذج إنشاء مستوى جديد
     */
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        
        // الحصول على المستويات الموجودة للاختيار كمتطلبات سابقة
        $existingLevels = $course->levels()->orderBy('order')->get();
        
        return view('levels.create', compact('course', 'existingLevels'));
    }

    /**
     * حفظ مستوى جديد
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:levels,id'
        ]);
        
        // تحويل المتطلبات السابقة إلى تنسيق JSON
        if (isset($validated['prerequisites'])) {
            $validated['prerequisites'] = array_filter($validated['prerequisites']);
        } else {
            $validated['prerequisites'] = null;
        }
        
        $validated['course_id'] = $course->id;
        
        $level = Level::create($validated);
        
        return redirect()->route('courses.levels.show', [$course, $level])
            ->with('success', 'تم إنشاء المستوى بنجاح');
    }

    /**
     * عرض مستوى محدد
     */
    public function show(Course $course, Level $level)
    {
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $level->load(['contents' => function ($query) {
            $query->orderBy('order');
        }]);
        
        // التحقق من نوع المستخدم (مسؤول أو طالب)
        if (auth()->user()->can('update', $course)) {
            // عرض صفحة المستوى للمسؤولين
            return view('levels.show', compact('course', 'level'));
        } else {
            // التحقق من أن الطالب مسجل في الدورة
            $student = auth()->user();
            $enrollment = $student->enrolledCourses()->where('course_id', $course->id)->first();
            
            if (!$enrollment) {
                return redirect()->route('courses.catalog')
                    ->with('error', 'يجب التسجيل في الدورة أولاً للوصول إلى محتواها');
            }
            
            // عرض صفحة المستوى للطلاب
            return view('student.levels.show', compact('course', 'level'));
        }
    }

    /**
     * عرض نموذج تعديل مستوى
     */
    public function edit(Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        // الحصول على المستويات الموجودة للاختيار كمتطلبات سابقة
        $existingLevels = $course->levels()->where('id', '!=', $level->id)->orderBy('order')->get();
        
        return view('levels.edit', compact('course', 'level', 'existingLevels'));
    }

    /**
     * تحديث مستوى
     */
    public function update(Request $request, Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:levels,id'
        ]);
        
        // تحويل المتطلبات السابقة إلى تنسيق JSON
        if (isset($validated['prerequisites'])) {
            // التأكد من عدم وجود المستوى الحالي في المتطلبات السابقة
            $validated['prerequisites'] = array_filter($validated['prerequisites'], function ($id) use ($level) {
                return $id != $level->id;
            });
        } else {
            $validated['prerequisites'] = null;
        }
        
        $level->update($validated);
        
        return redirect()->route('courses.levels.show', [$course, $level])
            ->with('success', 'تم تحديث المستوى بنجاح');
    }

    /**
     * حذف مستوى
     */
    public function destroy(Course $course, Level $level)
    {
        $this->authorize('update', $course);
        
        // التأكد من أن المستوى ينتمي للدورة
        if ($level->course_id !== $course->id) {
            abort(404);
        }
        
        $level->delete();
        
        return redirect()->route('courses.levels.index', $course)
            ->with('success', 'تم حذف المستوى بنجاح');
    }
    
    /**
     * إعادة ترتيب المستويات
     */
    public function reorder(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $request->validate([
            'levels' => 'required|array',
            'levels.*' => 'exists:levels,id'
        ]);
        
        $levels = $request->input('levels');
        
        foreach ($levels as $order => $id) {
            Level::where('id', $id)->where('course_id', $course->id)->update(['order' => $order]);
        }
        
        return response()->json(['success' => true]);
    }
} 