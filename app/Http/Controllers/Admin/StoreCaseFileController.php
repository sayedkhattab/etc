<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store\StoreCaseAttachment;
use App\Models\Store\StoreCaseCategory;
use App\Models\Store\StoreCaseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\ActiveCase;

class StoreCaseFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $caseFiles = StoreCaseFile::with('category', 'admin')->paginate(10);
        return view('admin.store.case-files.index', compact('caseFiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = StoreCaseCategory::active()->ordered()->get();
        return view('admin.store.case-files.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:store_case_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'case_type' => ['required', Rule::in(['مدعي', 'مدعى عليه'])],
            'case_number' => 'nullable|string|max:50|unique:store_case_files,case_number',
            'facts' => 'nullable|string',
            'legal_articles' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'difficulty' => ['required', Rule::in(['سهل', 'متوسط', 'صعب'])],
            'estimated_duration_days' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('case-thumbnails', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['admin_id'] = Auth::id();
        $validated['purchases_count'] = 0;
        
        // استخدام رقم القضية المدخل أو توليد رقم تلقائي إذا كان فارغًا
        if (empty($validated['case_number'])) {
            $validated['case_number'] = StoreCaseFile::generateCaseNumber();
        }

        $caseFile = StoreCaseFile::create($validated);

        return redirect()->route('admin.store.case-files.edit', $caseFile->id)
            ->with('success', 'تم إنشاء ملف القضية بنجاح. يمكنك الآن إضافة المرفقات.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $caseFile = StoreCaseFile::with(['category', 'admin', 'attachments', 'purchases.user'])->findOrFail($id);
        return view('admin.store.case-files.show', compact('caseFile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $caseFile = StoreCaseFile::with('attachments')->findOrFail($id);
        $categories = StoreCaseCategory::active()->ordered()->get();
        return view('admin.store.case-files.edit', compact('caseFile', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $caseFile = StoreCaseFile::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:store_case_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'case_type' => ['required', Rule::in(['مدعي', 'مدعى عليه'])],
            'case_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('store_case_files')->ignore($caseFile->id)
            ],
            'facts' => 'nullable|string',
            'legal_articles' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'difficulty' => ['required', Rule::in(['سهل', 'متوسط', 'صعب'])],
            'estimated_duration_days' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($caseFile->thumbnail) {
                Storage::disk('public')->delete($caseFile->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('case-thumbnails', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        
        // إذا كان رقم القضية فارغًا وكان الرقم الحالي فارغًا، قم بتوليد رقم جديد
        if (empty($validated['case_number']) && empty($caseFile->case_number)) {
            $validated['case_number'] = StoreCaseFile::generateCaseNumber();
        }

        $caseFile->update($validated);

        return redirect()->route('admin.store.case-files.index')
            ->with('success', 'تم تحديث ملف القضية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $caseFile = StoreCaseFile::findOrFail($id);

        // Check if case file has purchases
        if ($caseFile->purchases()->count() > 0) {
            return redirect()->route('admin.store.case-files.index')
                ->with('error', 'لا يمكن حذف ملف القضية لأنه تم شراؤه من قبل المستخدمين');
        }

        // Delete thumbnail if exists
        if ($caseFile->thumbnail) {
            Storage::disk('public')->delete($caseFile->thumbnail);
        }

        // Delete attachments
        foreach ($caseFile->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $caseFile->delete();

        return redirect()->route('admin.store.case-files.index')
            ->with('success', 'تم حذف ملف القضية بنجاح');
    }

    /**
     * Add attachment to the case file.
     */
    public function addAttachment(Request $request, string $id)
    {
        $caseFile = StoreCaseFile::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'role' => ['required', Rule::in(['مدعي', 'مدعى عليه', 'عام'])],
            'is_visible_before_purchase' => 'boolean',
        ]);

        $filePath = $request->file('file')->store('case-attachments', 'public');
        $fileType = $request->file('file')->getClientOriginalExtension();

        StoreCaseAttachment::create([
            'case_file_id' => $caseFile->id,
            'title' => $validated['title'],
            'file_path' => $filePath,
            'file_type' => $fileType,
            'role' => $validated['role'],
            'is_visible_before_purchase' => $request->has('is_visible_before_purchase'),
        ]);

        return redirect()->route('admin.store.case-files.edit', $caseFile->id)
            ->with('success', 'تم إضافة المرفق بنجاح');
    }

    /**
     * Remove attachment from the case file.
     */
    public function removeAttachment(string $id, string $attachmentId)
    {
        $caseFile = StoreCaseFile::findOrFail($id);
        $attachment = StoreCaseAttachment::where('case_file_id', $caseFile->id)
            ->where('id', $attachmentId)
            ->firstOrFail();

        // Delete file
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return redirect()->route('admin.store.case-files.edit', $caseFile->id)
            ->with('success', 'تم حذف المرفق بنجاح');
    }

    /**
     * Download an attachment.
     */
    public function downloadAttachment(string $attachmentId)
    {
        $attachment = StoreCaseAttachment::findOrFail($attachmentId);
        
        return Storage::disk('public')->download(
            $attachment->file_path, 
            $attachment->title . '.' . $attachment->file_type
        );
    }

    /**
     * Activate a case file in the virtual court.
     */
    public function activate(string $id)
    {
        $caseFile = StoreCaseFile::with(['purchases'])->findOrFail($id);

        // Check if case file can be activated
        if (!$caseFile->canActivateInCourt()) {
            return redirect()->route('admin.store.case-files.show', $id)
                ->with('error', 'لا يمكن تفعيل هذه القضية في المحكمة الافتراضية. يجب أن يتم شراؤها من قبل مستخدمين مختلفين بدوري المدعي والمدعى عليه.');
        }

        // Get plaintiff and defendant purchases
        $plaintiffPurchase = $caseFile->getPurchaseByRole('مدعي');
        $defendantPurchase = $caseFile->getPurchaseByRole('مدعى عليه');

        DB::beginTransaction();
        try {
            // Create active case
            $activeCase = ActiveCase::create([
                'store_case_file_id' => $caseFile->id,
                'title' => $caseFile->title,
                'status' => 'active',
                'plaintiff_id' => $plaintiffPurchase->user_id,
                'defendant_id' => $defendantPurchase->user_id,
                'started_at' => now(),
                'expected_end_at' => now()->addDays($caseFile->estimated_duration_days),
            ]);

            // Update purchases
            $plaintiffPurchase->activate($activeCase->id);
            $defendantPurchase->activate($activeCase->id);

            DB::commit();

            return redirect()->route('admin.store.case-files.show', $id)
                ->with('success', 'تم تفعيل القضية في المحكمة الافتراضية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.store.case-files.show', $id)
                ->with('error', 'حدث خطأ أثناء تفعيل القضية: ' . $e->getMessage());
        }
    }
}
