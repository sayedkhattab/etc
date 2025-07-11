<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $levels = $course->levels()
            ->with(['contents.contentType', 'assessments.questions'])
            ->orderBy('order')
            ->get();
        
        return view('admin.levels.index', compact('course', 'levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        $existingLevels = $course->levels()->orderBy('order')->get();
        return view('admin.levels.create', compact('course', 'existingLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:levels,id',
        ]);

        $validated['prerequisites'] = $validated['prerequisites'] ?? null;
        $validated['course_id'] = $course->id;

        Level::create($validated);

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', 'تم إنشاء المستوى بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Level $level)
    {
        if ($level->course_id !== $course->id) {
            abort(404);
        }

        $level->load(['contents' => function ($q) {
            $q->orderBy('order');
        }]);

        return view('admin.levels.show', compact('course', 'level'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Level $level)
    {
        if ($level->course_id !== $course->id) {
            abort(404);
        }

        $existingLevels = $course->levels()->where('id', '!=', $level->id)->orderBy('order')->get();

        return view('admin.levels.edit', compact('course', 'level', 'existingLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Level $level)
    {
        if ($level->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:levels,id',
        ]);

        if (isset($validated['prerequisites'])) {
            $validated['prerequisites'] = array_filter($validated['prerequisites'], function ($id) use ($level) {
                return $id != $level->id;
            });
        } else {
            $validated['prerequisites'] = null;
        }

        $level->update($validated);

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', 'تم تحديث المستوى بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Level $level)
    {
        if ($level->course_id !== $course->id) {
            abort(404);
        }

        $level->delete();

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', 'تم حذف المستوى بنجاح');
    }

    /**
     * Reorder levels.
     */
    public function reorder(Request $request, Course $course)
    {
        $request->validate([
            'levels' => 'required|array',
            'levels.*' => 'exists:levels,id',
        ]);

        foreach ($request->levels as $index => $id) {
            Level::where('id', $id)->where('course_id', $course->id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
} 