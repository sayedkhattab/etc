<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store\StoreCaseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StoreCaseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = StoreCaseCategory::orderBy('sort_order')->get();
        return view('admin.store.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.store.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:store_case_categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('category-icons', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        StoreCaseCategory::create($validated);

        return redirect()->route('admin.store.categories.index')
            ->with('success', 'تم إنشاء فئة القضايا بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = StoreCaseCategory::findOrFail($id);
        return view('admin.store.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = StoreCaseCategory::findOrFail($id);
        return view('admin.store.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = StoreCaseCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('store_case_categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($category->icon) {
                Storage::disk('public')->delete($category->icon);
            }
            $validated['icon'] = $request->file('icon')->store('category-icons', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? $category->sort_order;
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.store.categories.index')
            ->with('success', 'تم تحديث فئة القضايا بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = StoreCaseCategory::findOrFail($id);

        // Check if category has case files
        if ($category->caseFiles()->count() > 0) {
            return redirect()->route('admin.store.categories.index')
                ->with('error', 'لا يمكن حذف الفئة لأنها تحتوي على ملفات قضايا');
        }

        // Delete icon if exists
        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();

        return redirect()->route('admin.store.categories.index')
            ->with('success', 'تم حذف فئة القضايا بنجاح');
    }

    /**
     * Update the sort order of categories.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:store_case_categories,id',
            'categories.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['categories'] as $categoryData) {
            StoreCaseCategory::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['order']]);
        }

        return response()->json(['success' => true]);
    }
}
