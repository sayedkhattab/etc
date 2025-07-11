<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VirtualCourt\CaseFile;
use App\Models\VirtualCourt\CaseFileAttachment;
use App\Models\VirtualCourt\ActiveCase;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CourtArchiveController extends Controller
{
    /**
     * عرض قائمة القضايا المؤرشفة
     */
    public function index(Request $request)
    {
        $query = CaseFile::query();
        
        // البحث حسب رقم القضية
        if ($request->has('case_number') && !empty($request->case_number)) {
            $query->where('case_number', 'like', '%' . $request->case_number . '%');
        }
        
        // البحث حسب عنوان القضية
        if ($request->has('title') && !empty($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        
        // البحث حسب نوع القضية
        if ($request->has('case_type') && !empty($request->case_type)) {
            $query->where('case_type', $request->case_type);
        }
        
        // البحث حسب تاريخ الإنشاء
        if ($request->has('created_at') && !empty($request->created_at)) {
            $query->whereDate('created_at', $request->created_at);
        }
        
        $caseFiles = $query->with(['attachments', 'activeCases'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
        
        return view('admin.court-archives.index', compact('caseFiles'));
    }

    /**
     * عرض نموذج إنشاء قضية جديدة في الأرشيف
     */
    public function create()
    {
        return view('admin.court-archives.create');
    }

    /**
     * حفظ قضية جديدة في الأرشيف
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_number' => 'required|string|max:50|unique:case_files,case_number',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'case_type' => 'required|string|in:مدني,جنائي,تجاري,إداري,أحوال شخصية,عمالي',
            'plaintiff' => 'required|string|max:255',
            'defendant' => 'required|string|max:255',
            'court_name' => 'required|string|max:255',
            'judge_name' => 'nullable|string|max:255',
            'status' => 'required|string|in:جاري,مكتمل,مؤجل,مغلق',
            'judgment_summary' => 'nullable|string',
            'judgment_date' => 'nullable|date',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $validated['admin_id'] = Auth::id();
        
        $caseFile = CaseFile::create($validated);

        // معالجة المرفقات
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('case-attachments', 'public');
                
                $caseFile->attachments()->create([
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'admin_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('admin.court-archives.show', $caseFile->id)
            ->with('success', 'تم إضافة القضية إلى الأرشيف بنجاح');
    }

    /**
     * عرض تفاصيل قضية محددة من الأرشيف
     */
    public function show($id)
    {
        $caseFile = CaseFile::with(['attachments', 'activeCases', 'admin'])->findOrFail($id);
        return view('admin.court-archives.show', compact('caseFile'));
    }

    /**
     * عرض نموذج تعديل قضية محددة من الأرشيف
     */
    public function edit($id)
    {
        $caseFile = CaseFile::with(['attachments'])->findOrFail($id);
        return view('admin.court-archives.edit', compact('caseFile'));
    }

    /**
     * تحديث قضية محددة في الأرشيف
     */
    public function update(Request $request, $id)
    {
        $caseFile = CaseFile::findOrFail($id);
        
        $validated = $request->validate([
            'case_number' => 'required|string|max:50|unique:case_files,case_number,' . $id,
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'case_type' => 'required|string|in:مدني,جنائي,تجاري,إداري,أحوال شخصية,عمالي',
            'plaintiff' => 'required|string|max:255',
            'defendant' => 'required|string|max:255',
            'court_name' => 'required|string|max:255',
            'judge_name' => 'nullable|string|max:255',
            'status' => 'required|string|in:جاري,مكتمل,مؤجل,مغلق',
            'judgment_summary' => 'nullable|string',
            'judgment_date' => 'nullable|date',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $caseFile->update($validated);

        // معالجة المرفقات
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('case-attachments', 'public');
                
                $caseFile->attachments()->create([
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'admin_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('admin.court-archives.show', $caseFile->id)
            ->with('success', 'تم تحديث بيانات القضية بنجاح');
    }

    /**
     * حذف قضية محددة من الأرشيف
     */
    public function destroy($id)
    {
        $caseFile = CaseFile::findOrFail($id);
        
        // حذف المرفقات من التخزين
        foreach ($caseFile->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }
        
        $caseFile->delete();
        
        return redirect()->route('admin.court-archives.index')
            ->with('success', 'تم حذف القضية من الأرشيف بنجاح');
    }
}
