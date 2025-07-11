<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\CaseAttachment;
use App\Models\CaseParticipant;
use App\Models\CaseStatus;
use App\Models\CourtType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CaseController extends Controller
{
    /**
     * عرض قائمة القضايا
     */
    public function index()
    {
        // التحقق من الصلاحيات
        $this->authorize('viewAny', CaseModel::class);
        
        $user = Auth::user();
        
        // إذا كان المستخدم قاضي، يرى القضايا المسندة إليه
        if ($user->hasRole('judge')) {
            $cases = CaseModel::where('judge_id', $user->id)
                ->with(['status', 'courtType'])
                ->paginate(20);
        } 
        // إذا كان المستخدم طالب، يرى القضايا التي يشارك فيها
        else if ($user->hasRole('student')) {
            $cases = CaseModel::whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orWhere('defendant_id', $user->id)
            ->with(['status', 'courtType'])
            ->paginate(20);
        } 
        // المسؤول يرى جميع القضايا
        else {
            $cases = CaseModel::with(['status', 'courtType'])
                ->paginate(20);
        }
        
        return view('cases.index', compact('cases'));
    }

    /**
     * عرض نموذج إنشاء قضية جديدة
     */
    public function create()
    {
        $this->authorize('create', CaseModel::class);
        
        $courtTypes = CourtType::all();
        $caseStatuses = CaseStatus::all();
        $judges = User::whereHas('role', function ($query) {
            $query->where('name', 'judge');
        })->get();
        
        return view('cases.create', compact('courtTypes', 'caseStatuses', 'judges'));
    }

    /**
     * حفظ قضية جديدة
     */
    public function store(Request $request)
    {
        $this->authorize('create', CaseModel::class);
        
        $validated = $request->validate([
            'request_id' => 'required|exists:requests,id',
            'judge_id' => 'required|exists:users,id',
            'defendant_id' => 'nullable|exists:users,id',
            'status_id' => 'required|exists:case_statuses,id',
            'court_type_id' => 'required|exists:court_types,id',
            'start_date' => 'required|date',
            'close_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        // إنشاء رقم فريد للقضية
        $validated['case_number'] = CaseModel::generateCaseNumber();
        
        // إنشاء القضية
        $case = CaseModel::create($validated);
        
        return redirect()->route('cases.show', $case)
            ->with('success', 'تم إنشاء القضية بنجاح');
    }

    /**
     * عرض قضية محددة
     */
    public function show(CaseModel $case)
    {
        $this->authorize('view', $case);
        
        $case->load([
            'status', 
            'courtType', 
            'judge', 
            'defendant', 
            'participants.user',
            'participants.role',
            'attachments' => function ($query) {
                // عرض المرفقات العامة أو المرفقات التي يملكها المستخدم
                $query->where('visibility', 'public')
                    ->orWhere('uploaded_by', Auth::id());
            },
            'sessions' => function ($query) {
                $query->orderBy('date_time');
            },
            'judgments' => function ($query) {
                $query->where('status', 'published')
                    ->orWhere('status', 'final');
            },
        ]);
        
        return view('cases.show', compact('case'));
    }

    /**
     * عرض نموذج تعديل قضية
     */
    public function edit(CaseModel $case)
    {
        $this->authorize('update', $case);
        
        $courtTypes = CourtType::all();
        $caseStatuses = CaseStatus::all();
        $judges = User::whereHas('role', function ($query) {
            $query->where('name', 'judge');
        })->get();
        
        return view('cases.edit', compact('case', 'courtTypes', 'caseStatuses', 'judges'));
    }

    /**
     * تحديث قضية
     */
    public function update(Request $request, CaseModel $case)
    {
        $this->authorize('update', $case);
        
        $validated = $request->validate([
            'judge_id' => 'required|exists:users,id',
            'defendant_id' => 'nullable|exists:users,id',
            'status_id' => 'required|exists:case_statuses,id',
            'court_type_id' => 'required|exists:court_types,id',
            'start_date' => 'required|date',
            'close_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $case->update($validated);
        
        return redirect()->route('cases.show', $case)
            ->with('success', 'تم تحديث القضية بنجاح');
    }

    /**
     * حذف قضية
     */
    public function destroy(CaseModel $case)
    {
        $this->authorize('delete', $case);
        
        // حذف المرفقات المرتبطة بالقضية
        foreach ($case->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        
        $case->delete();
        
        return redirect()->route('cases.index')
            ->with('success', 'تم حذف القضية بنجاح');
    }
    
    /**
     * إضافة مرفق للقضية
     */
    public function addAttachment(Request $request, CaseModel $case)
    {
        $this->authorize('addAttachment', $case);
        
        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,private',
        ]);
        
        $file = $request->file('file');
        $path = $file->store('case_attachments/' . $case->id, 'public');
        
        CaseAttachment::create([
            'case_id' => $case->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $validated['description'],
            'uploaded_by' => Auth::id(),
            'visibility' => $validated['visibility'],
        ]);
        
        return redirect()->route('cases.show', $case)
            ->with('success', 'تم إضافة المرفق بنجاح');
    }
    
    /**
     * حذف مرفق
     */
    public function deleteAttachment(CaseModel $case, CaseAttachment $attachment)
    {
        // التأكد من أن المرفق ينتمي للقضية
        if ($attachment->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات (المالك أو المسؤول)
        if (Auth::id() !== $attachment->uploaded_by) {
            $this->authorize('deleteAttachment', $case);
        }
        
        // حذف الملف
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        $attachment->delete();
        
        return redirect()->route('cases.show', $case)
            ->with('success', 'تم حذف المرفق بنجاح');
    }
    
    /**
     * تنزيل مرفق
     */
    public function downloadAttachment(CaseModel $case, CaseAttachment $attachment)
    {
        // التأكد من أن المرفق ينتمي للقضية
        if ($attachment->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if ($attachment->visibility !== 'public' && Auth::id() !== $attachment->uploaded_by) {
            $this->authorize('viewAttachment', $case);
        }
        
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()->route('cases.show', $case)
                ->with('error', 'الملف غير موجود');
        }
        
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
    
    /**
     * إضافة مشارك للقضية
     */
    public function addParticipant(Request $request, CaseModel $case)
    {
        $this->authorize('update', $case);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        // التحقق من عدم وجود المشارك مسبقًا بنفس الدور
        if (CaseParticipant::where('case_id', $case->id)
            ->where('user_id', $validated['user_id'])
            ->where('role_id', $validated['role_id'])
            ->exists()) {
            return redirect()->route('cases.show', $case)
                ->with('error', 'المشارك موجود بالفعل بهذا الدور');
        }
        
        CaseParticipant::create([
            'case_id' => $case->id,
            'user_id' => $validated['user_id'],
            'role_id' => $validated['role_id'],
            'joined_at' => now(),
        ]);
        
        return redirect()->route('cases.show', $case)
            ->with('success', 'تمت إضافة المشارك بنجاح');
    }
    
    /**
     * حذف مشارك من القضية
     */
    public function removeParticipant(CaseModel $case, CaseParticipant $participant)
    {
        $this->authorize('update', $case);
        
        // التأكد من أن المشارك ينتمي للقضية
        if ($participant->case_id !== $case->id) {
            abort(404);
        }
        
        $participant->delete();
        
        return redirect()->route('cases.show', $case)
            ->with('success', 'تم حذف المشارك بنجاح');
    }
} 