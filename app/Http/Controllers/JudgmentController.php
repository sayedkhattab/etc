<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Judgment;
use App\Models\JudgmentAttachment;
use App\Models\JudgmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JudgmentController extends Controller
{
    /**
     * عرض قائمة الأحكام لقضية معينة
     */
    public function index(CaseModel $case)
    {
        $this->authorize('view', $case);
        
        // تحديد الأحكام التي يمكن عرضها بناءً على صلاحيات المستخدم
        if (Auth::user()->hasRole('judge') && Auth::id() === $case->judge_id) {
            // القاضي يرى جميع الأحكام بما فيها المسودات
            $judgments = $case->judgments()->with('judgmentType')->get();
        } else {
            // المستخدمون الآخرون يرون فقط الأحكام المنشورة أو النهائية
            $judgments = $case->judgments()
                ->whereIn('status', ['published', 'final'])
                ->with('judgmentType')
                ->get();
        }
        
        return view('judgments.index', compact('case', 'judgments'));
    }

    /**
     * عرض نموذج إنشاء حكم جديد
     */
    public function create(CaseModel $case)
    {
        $this->authorize('createJudgment', $case);
        
        $judgmentTypes = JudgmentType::all();
        
        return view('judgments.create', compact('case', 'judgmentTypes'));
    }

    /**
     * حفظ حكم جديد
     */
    public function store(Request $request, CaseModel $case)
    {
        $this->authorize('createJudgment', $case);
        
        $validated = $request->validate([
            'judgment_type_id' => 'required|exists:judgment_types,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'judgment_date' => 'required|date',
            'status' => 'required|in:draft,published,final',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);
        
        $validated['case_id'] = $case->id;
        
        $judgment = Judgment::create($validated);
        
        // معالجة المرفقات
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('judgment_attachments/' . $case->id . '/' . $judgment->id, 'public');
                
                JudgmentAttachment::create([
                    'judgment_id' => $judgment->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
        
        // إذا كان الحكم نهائيًا، يتم إغلاق القضية
        if ($validated['status'] === 'final' && !$case->isClosed()) {
            $case->update([
                'close_date' => now(),
                'status_id' => CaseStatus::where('name', 'closed')->first()->id,
            ]);
        }
        
        return redirect()->route('cases.judgments.show', [$case, $judgment])
            ->with('success', 'تم إنشاء الحكم بنجاح');
    }

    /**
     * عرض حكم محدد
     */
    public function show(CaseModel $case, Judgment $judgment)
    {
        // التأكد من أن الحكم ينتمي للقضية
        if ($judgment->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if ($judgment->status === 'draft' && Auth::id() !== $case->judge_id) {
            $this->authorize('viewDraftJudgment', $case);
        } else {
            $this->authorize('view', $case);
        }
        
        $judgment->load(['judgmentType', 'attachments']);
        
        return view('judgments.show', compact('case', 'judgment'));
    }

    /**
     * عرض نموذج تعديل حكم
     */
    public function edit(CaseModel $case, Judgment $judgment)
    {
        // التأكد من أن الحكم ينتمي للقضية
        if ($judgment->case_id !== $case->id) {
            abort(404);
        }
        
        $this->authorize('updateJudgment', $case);
        
        // لا يمكن تعديل الأحكام النهائية
        if ($judgment->status === 'final') {
            return redirect()->route('cases.judgments.show', [$case, $judgment])
                ->with('error', 'لا يمكن تعديل الأحكام النهائية');
        }
        
        $judgmentTypes = JudgmentType::all();
        
        return view('judgments.edit', compact('case', 'judgment', 'judgmentTypes'));
    }

    /**
     * تحديث حكم
     */
    public function update(Request $request, CaseModel $case, Judgment $judgment)
    {
        // التأكد من أن الحكم ينتمي للقضية
        if ($judgment->case_id !== $case->id) {
            abort(404);
        }
        
        $this->authorize('updateJudgment', $case);
        
        // لا يمكن تعديل الأحكام النهائية
        if ($judgment->status === 'final') {
            return redirect()->route('cases.judgments.show', [$case, $judgment])
                ->with('error', 'لا يمكن تعديل الأحكام النهائية');
        }
        
        $validated = $request->validate([
            'judgment_type_id' => 'required|exists:judgment_types,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'judgment_date' => 'required|date',
            'status' => 'required|in:draft,published,final',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);
        
        $judgment->update($validated);
        
        // معالجة المرفقات
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('judgment_attachments/' . $case->id . '/' . $judgment->id, 'public');
                
                JudgmentAttachment::create([
                    'judgment_id' => $judgment->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
        
        // إذا كان الحكم نهائيًا، يتم إغلاق القضية
        if ($validated['status'] === 'final' && !$case->isClosed()) {
            $case->update([
                'close_date' => now(),
                'status_id' => CaseStatus::where('name', 'closed')->first()->id,
            ]);
        }
        
        return redirect()->route('cases.judgments.show', [$case, $judgment])
            ->with('success', 'تم تحديث الحكم بنجاح');
    }

    /**
     * حذف حكم
     */
    public function destroy(CaseModel $case, Judgment $judgment)
    {
        // التأكد من أن الحكم ينتمي للقضية
        if ($judgment->case_id !== $case->id) {
            abort(404);
        }
        
        $this->authorize('deleteJudgment', $case);
        
        // لا يمكن حذف الأحكام النهائية
        if ($judgment->status === 'final') {
            return redirect()->route('cases.judgments.show', [$case, $judgment])
                ->with('error', 'لا يمكن حذف الأحكام النهائية');
        }
        
        // حذف المرفقات
        foreach ($judgment->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        
        $judgment->delete();
        
        return redirect()->route('cases.judgments.index', $case)
            ->with('success', 'تم حذف الحكم بنجاح');
    }
    
    /**
     * تنزيل مرفق
     */
    public function downloadAttachment(CaseModel $case, Judgment $judgment, JudgmentAttachment $attachment)
    {
        // التأكد من أن الحكم والمرفق ينتميان للقضية
        if ($judgment->case_id !== $case->id || $attachment->judgment_id !== $judgment->id) {
            abort(404);
        }
        
        // التحقق من الصلاحيات
        if ($judgment->status === 'draft' && Auth::id() !== $case->judge_id) {
            $this->authorize('viewDraftJudgment', $case);
        } else {
            $this->authorize('view', $case);
        }
        
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()->route('cases.judgments.show', [$case, $judgment])
                ->with('error', 'الملف غير موجود');
        }
        
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
    
    /**
     * حذف مرفق
     */
    public function deleteAttachment(CaseModel $case, Judgment $judgment, JudgmentAttachment $attachment)
    {
        // التأكد من أن الحكم والمرفق ينتميان للقضية
        if ($judgment->case_id !== $case->id || $attachment->judgment_id !== $judgment->id) {
            abort(404);
        }
        
        $this->authorize('updateJudgment', $case);
        
        // لا يمكن تعديل الأحكام النهائية
        if ($judgment->status === 'final') {
            return redirect()->route('cases.judgments.show', [$case, $judgment])
                ->with('error', 'لا يمكن تعديل الأحكام النهائية');
        }
        
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        $attachment->delete();
        
        return redirect()->route('cases.judgments.show', [$case, $judgment])
            ->with('success', 'تم حذف المرفق بنجاح');
    }
} 