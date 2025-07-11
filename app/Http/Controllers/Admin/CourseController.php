<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('category', 'creator')->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $categories = CourseCategory::all();
        return view('admin.courses.create', compact('categories'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:course_categories,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'thumbnail'        => 'nullable|image|max:2048',
            'price'            => 'required|numeric|min:0',
            'duration'         => 'nullable|integer|min:1',
            'status'           => 'required|in:draft,active,inactive,archived',
            'visibility'       => 'required|in:public,private,password_protected',
            'access_password'  => 'nullable|required_if:visibility,password_protected|string|min:6',
            'featured'         => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        // Use admin guard to correctly capture the creator ID
        $validated['created_by'] = Auth::guard('admin')->id();

        $course = Course::create($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'تم إنشاء الدورة بنجاح');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['category', 'creator', 'levels' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $categories = CourseCategory::all();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:course_categories,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'thumbnail'        => 'nullable|image|max:2048',
            'price'            => 'required|numeric|min:0',
            'duration'         => 'nullable|integer|min:1',
            'status'           => 'required|in:draft,active,inactive,archived',
            'visibility'       => 'required|in:public,private,password_protected',
            'access_password'  => 'nullable|required_if:visibility,password_protected|string|min:6',
            'featured'         => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $path = $request->file('thumbnail')->store('course_thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $course->update($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'تم تحديث الدورة بنجاح');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Delete thumbnail if exists
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'تم حذف الدورة بنجاح');
    }
} 