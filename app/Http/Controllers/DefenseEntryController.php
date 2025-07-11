<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\DefenseEntry;
use App\Models\DefenseAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DefenseEntryController extends Controller
{
    /**
     * عرض قائمة المذكرات الدفاعية لقضية معينة
     */
    public function index(CaseModel $case)
    {
        $this->authorize('view', $case);
        
        // تحديد المذكرات التي يمكن عرضها بناءً على صلاحيات المستخدم
        if (Auth::user()->hasRole('judge') || Auth::user()->hasRole('admin')) {
            // القاضي والمسؤول يرون جميع المذكرات
            $defenseEntries = $case->defenseEntries()->with('student')->get();
        } else {
            // الطلاب يرون فقط مذكراتهم
            $defenseEntries = $case->defenseEntries()
                ->where('student_id', Auth::id())
                ->with('student')
                ->get();
        }
        
        return view('defense_entries.index', compact('case', 'defenseEntries'));
    }

    /**
     * عرض نموذج إنشاء مذكرة دفاعية جديدة
     */
    public function create(CaseModel $case)
    {
        $this->authorize('createDefenseEntry', $case);
        
        return view('defense_entries.create', compact('case'));
    }

    /**
     * حفظ مذكرة دفاعية جديدة
     */
    public function store(Request $request, CaseModel $case)
    {
        $this->authorize('createDefenseEntry', $case);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,submitted',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);
        
        $validated['case_id'] = $case->id;
        $validated['student_id'] = Auth::id();
        
        // إذا كانت الحالة "مقدمة"، نضيف تاريخ التقديم
        if ($validated['status'] === 'submitted') {
            $validated['submitted_at'] = now();
        }
        
        $defenseEntry = DefenseEntry::create($validated);
        
        // معالجة المرفقات
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('defense_attachments/' . $case->id . '/' . $defenseEntry->id, 'public');
                
                DefenseAttachment::create([
                    'defense_entry_id' => $defenseEntry->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
        
        return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
            ->with('success', 'تم إنشاء المذكرة الدفاعية بنجاح');
    }

    /**
     * عرض مذكرة دفاعية محددة
     */
    public function show(CaseModel $case, DefenseEntry $defenseEntry)
    {
        // التأكد من أن المذكرة تنتمي للقضية
        if ($defenseEntry->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if (Auth::id() !== $defenseEntry->student_id) {
            $this->authorize('viewDefenseEntry', $case);
        }
        
        $defenseEntry->load(['student', 'attachments']);
        
        return view('defense_entries.show', compact('case', 'defenseEntry'));
    }

    /**
     * عرض نموذج تعديل مذكرة دفاعية
     */
    public function edit(CaseModel $case, DefenseEntry $defenseEntry)
    {
        // التأكد من أن المذكرة تنتمي للقضية
        if ($defenseEntry->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if (Auth::id() !== $defenseEntry->student_id) {
            $this->authorize('updateDefenseEntry', $case);
        }
        
        // لا يمكن تعديل المذكرات المراجعة
        if ($defenseEntry->status === 'reviewed') {
            return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
                ->with('error', 'لا يمكن تعديل المذكرات التي تمت مراجعتها');
        }
        
        return view('defense_entries.edit', compact('case', 'defenseEntry'));
    }

    /**
     * تحديث مذكرة دفاعية
     */
    public function update(Request $request, CaseModel $case, DefenseEntry $defenseEntry)
    {
        // التأكد من أن المذكرة تنتمي للقضية
        if ($defenseEntry->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if (Auth::id() !== $defenseEntry->student_id) {
            $this->authorize('updateDefenseEntry', $case);
        }
        
        // لا يمكن تعديل المذكرات المراجعة
        if ($defenseEntry->status === 'reviewed') {
            return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
                ->with('error', 'لا يمكن تعديل المذكرات التي تمت مراجعتها');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,submitted',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);
        
        // إذا كانت الحالة "مقدمة" ولم تكن كذلك من قبل، نضيف تاريخ التقديم
        if ($validated['status'] === 'submitted' && $defenseEntry->status !== 'submitted') {
            $validated['submitted_at'] = now();
        }
        
        $defenseEntry->update($validated);
        
        // معالجة المرفقات
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('defense_attachments/' . $case->id . '/' . $defenseEntry->id, 'public');
                
                DefenseAttachment::create([
                    'defense_entry_id' => $defenseEntry->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
        
        return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
            ->with('success', 'تم تحديث المذكرة الدفاعية بنجاح');
    }

    /**
     * حذف مذكرة دفاعية
     */
    public function destroy(CaseModel $case, DefenseEntry $defenseEntry)
    {
        // التأكد من أن المذكرة تنتمي للقضية
        if ($defenseEntry->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if (Auth::id() !== $defenseEntry->student_id) {
            $this->authorize('deleteDefenseEntry', $case);
        }
        
        // لا يمكن حذف المذكرات المراجعة
        if ($defenseEntry->status === 'reviewed') {
            return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
                ->with('error', 'لا يمكن حذف المذكرات التي تمت مراجعتها');
        }
        
        // حذف المرفقات
        foreach ($defenseEntry->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        
        $defenseEntry->delete();
        
        return redirect()->route('cases.defense_entries.index', $case)
            ->with('success', 'تم حذف المذكرة الدفاعية بنجاح');
    }
    
    /**
     * تنزيل مرفق
     */
    public function downloadAttachment(CaseModel $case, DefenseEntry $defenseEntry, DefenseAttachment $attachment)
    {
        // التأكد من أن المذكرة والمرفق ينتميان للقضية
        if ($defenseEntry->case_id !== $case->id || $attachment->defense_entry_id !== $defenseEntry->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if (Auth::id() !== $defenseEntry->student_id) {
            $this->authorize('viewDefenseEntry', $case);
        }
        
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
                ->with('error', 'الملف غير موجود');
        }
        
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
    
    /**
     * حذف مرفق
     */
    public function deleteAttachment(CaseModel $case, DefenseEntry $defenseEntry, DefenseAttachment $attachment)
    {
        // التأكد من أن المذكرة والمرفق ينتميان للقضية
        if ($defenseEntry->case_id !== $case->id || $attachment->defense_entry_id !== $defenseEntry->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if (Auth::id() !== $defenseEntry->student_id) {
            $this->authorize('updateDefenseEntry', $case);
        }
        
        // لا يمكن تعديل المذكرات المراجعة
        if ($defenseEntry->status === 'reviewed') {
            return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
                ->with('error', 'لا يمكن تعديل المذكرات التي تمت مراجعتها');
        }
        
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        $attachment->delete();
        
        return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
            ->with('success', 'تم حذف المرفق بنجاح');
    }
    
    /**
     * مراجعة المذكرة الدفاعية (للقاضي)
     */
    public function review(Request $request, CaseModel $case, DefenseEntry $defenseEntry)
    {
        // التأكد من أن المذكرة تنتمي للقضية
        if ($defenseEntry->case_id !== $case->id) {
            abort(404);
        }
        
        $this->authorize('reviewDefenseEntry', $case);
        
        // لا يمكن مراجعة المذكرات غير المقدمة
        if ($defenseEntry->status !== 'submitted') {
            return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
                ->with('error', 'لا يمكن مراجعة المذكرات غير المقدمة');
        }
        
        $validated = $request->validate([
            'feedback' => 'required|string',
        ]);
        
        $defenseEntry->update([
            'status' => 'reviewed',
            'feedback' => $validated['feedback'],
        ]);
        
        return redirect()->route('cases.defense_entries.show', [$case, $defenseEntry])
            ->with('success', 'تمت مراجعة المذكرة الدفاعية بنجاح');
    }
} 