<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CourseCategory::with('parent')->orderBy('display_order')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = CourseCategory::all();
        return view('admin.categories.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:course_categories,id',
            'display_order' => 'nullable|integer|min:0',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['display_order'] = $validated['display_order'] ?? 0;

        CourseCategory::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'تم إنشاء التصنيف بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseCategory $category)
    {
        $parents = CourseCategory::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('course_categories', 'name')->ignore($category->id)],
            'description' => 'nullable|string',
            'parent_id' => ['nullable', 'exists:course_categories,id', 'not_in:' . $category->id],
            'display_order' => 'nullable|integer|min:0',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['display_order'] = $validated['display_order'] ?? $category->display_order;

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseCategory $category)
    {
        if ($category->courses()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'لا يمكن حذف التصنيف لارتباطه بدورات موجودة');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }
} 