<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\Level;
use App\Models\Course;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Display a listing of assessments.
     */
    public function index(Request $request)
    {
        $query = Assessment::with(['assessmentType', 'level.course']);
        
        // Filter by course_id if provided
        if ($request->has('course_id')) {
            $query->whereHas('level', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }
        
        $assessments = $query->paginate(20);
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create(Request $request)
    {
        $assessmentTypes = AssessmentType::all();
        
        // If course_id is provided, only show levels for that course
        if ($request->has('course_id')) {
            $course = Course::findOrFail($request->course_id);
            $levels = Level::where('course_id', $course->id)->with('course')->get();
        } else {
            $levels = Level::with('course')->get();
        }
        
        return view('admin.assessments.create', compact('assessmentTypes', 'levels'));
    }

    /**
     * Store a newly created assessment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:0',
            'attempts_allowed' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'is_pretest' => 'required|boolean',
        ]);

        // Convert boolean values from string to actual boolean
        $validated['is_active'] = (bool)$request->is_active;
        $validated['is_pretest'] = (bool)$request->is_pretest;

        $assessment = Assessment::create($validated);

        return redirect()->route('admin.assessments.show', $assessment)
            ->with('success', 'تم إنشاء التقييم بنجاح.');
    }

    /**
     * Update the specified assessment.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:0',
            'attempts_allowed' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'is_pretest' => 'required|boolean',
        ]);

        // Convert boolean values from string to actual boolean
        $validated['is_active'] = (bool)$request->is_active;
        $validated['is_pretest'] = (bool)$request->is_pretest;

        $assessment->update($validated);

        return redirect()->route('admin.assessments.show', $assessment)
            ->with('success', 'تم تحديث التقييم بنجاح.');
    }

    /**
     * Display the specified assessment.
     */
    public function show(Assessment $assessment)
    {
        $assessment->load(['assessmentType', 'level.course']);
        return view('admin.assessments.show', compact('assessment'));
    }

    /**
     * Show the form for editing the specified assessment.
     */
    public function edit(Assessment $assessment)
    {
        $assessmentTypes = AssessmentType::all();
        $levels = Level::with('course')->get();
        
        return view('admin.assessments.edit', compact('assessment', 'assessmentTypes', 'levels'));
    }

    /**
     * Remove the specified assessment from storage.
     */
    public function destroy(Assessment $assessment)
    {
        $assessment->delete();
        return redirect()->route('admin.assessments.index')->with('success', 'تم حذف التقييم بنجاح');
    }
} 