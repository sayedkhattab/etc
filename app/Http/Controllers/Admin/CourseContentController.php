<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\ContentType;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseContentController extends Controller
{
    /**
     * Display a listing of content for a level (optional).
     */
    public function index(Course $course, Level $level)
    {
        if ($level->course_id !== $course->id) {
            abort(404);
        }

        $contents = $level->contents()->orderBy('order')->get();

        return view('admin.contents.index', compact('course', 'level', 'contents'));
    }

    /**
     * Show form to create new content.
     */
    public function create(Course $course, Level $level)
    {
        if ($level->course_id !== $course->id) {
            abort(404);
        }

        $contentTypes = ContentType::all();

        return view('admin.contents.create', compact('course', 'level', 'contentTypes'));
    }

    /**
     * Store newly created content.
     */
    public function store(Request $request, Course $course, Level $level)
    {
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

        if ($request->hasFile('content_file')) {
            $file = $request->file('content_file');
            $path = $file->store('course_contents/' . $course->id . '/' . $level->id, 'public');
            $validated['content_url'] = $path;
        }

        $validated['level_id'] = $level->id;

        CourseContent::create($validated);

        return redirect()->route('admin.courses.levels.contents.index', [$course, $level])
            ->with('success', 'تم إضافة المحتوى بنجاح');
    }

    /**
     * Show form to edit content.
     */
    public function edit(Course $course, Level $level, CourseContent $content)
    {
        if ($level->course_id !== $course->id || $content->level_id !== $level->id) {
            abort(404);
        }

        $contentTypes = ContentType::all();

        return view('admin.contents.edit', compact('course', 'level', 'content', 'contentTypes'));
    }

    /**
     * Update content.
     */
    public function update(Request $request, Course $course, Level $level, CourseContent $content)
    {
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

        if ($request->hasFile('content_file')) {
            if ($content->content_url && Storage::disk('public')->exists($content->content_url)) {
                Storage::disk('public')->delete($content->content_url);
            }
            $file = $request->file('content_file');
            $path = $file->store('course_contents/' . $course->id . '/' . $level->id, 'public');
            $validated['content_url'] = $path;
        }

        $content->update($validated);

        return redirect()->route('admin.courses.levels.contents.index', [$course, $level])
            ->with('success', 'تم تحديث المحتوى بنجاح');
    }

    /**
     * Remove content.
     */
    public function destroy(Course $course, Level $level, CourseContent $content)
    {
        if ($level->course_id !== $course->id || $content->level_id !== $level->id) {
            abort(404);
        }

        if ($content->content_url && Storage::disk('public')->exists($content->content_url)) {
            Storage::disk('public')->delete($content->content_url);
        }

        $content->delete();

        return redirect()->route('admin.courses.levels.contents.index', [$course, $level])
            ->with('success', 'تم حذف المحتوى بنجاح');
    }

    /**
     * Reorder contents.
     */
    public function reorder(Request $request, Course $course, Level $level)
    {
        $request->validate([
            'contents' => 'required|array',
            'contents.*' => 'exists:course_contents,id',
        ]);

        foreach ($request->contents as $index => $id) {
            CourseContent::where('id', $id)->where('level_id', $level->id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
} 