<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseModel;
use App\Models\CaseStatus;
use App\Models\CourtType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    /**
     * Display a listing of cases in the admin dashboard.
     */
    public function index()
    {
        $cases = CaseModel::with(['status', 'courtType', 'judge'])->paginate(20);
        return view('admin.cases.index', compact('cases'));
    }

    /**
     * Show the form for creating a new case.
     */
    public function create()
    {
        $courtTypes   = CourtType::all();
        $caseStatuses = CaseStatus::all();
        $judges       = User::whereHas('role', function ($q) {
            $q->where('name', 'judge');
        })->get();

        return view('admin.cases.create', compact('courtTypes', 'caseStatuses', 'judges'));
    }

    /**
     * Store a newly created case in storage (basic version).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judge_id'      => 'required|exists:users,id',
            'status_id'     => 'required|exists:case_statuses,id',
            'court_type_id' => 'required|exists:court_types,id',
            'start_date'    => 'required|date',
            'close_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        // Generate unique case number
        $validated['case_number'] = CaseModel::generateCaseNumber();

        // For now associate with the first available request (or null if none)
        $validated['request_id'] = $request->input('request_id') ?? 1; // TODO: adjust according to business flow

        $case = CaseModel::create($validated);

        return redirect()->route('admin.cases.show', $case)->with('success', 'تم إنشاء القضية بنجاح');
    }

    /**
     * Display the specified case.
     */
    public function show(CaseModel $case)
    {
        $case->load(['status', 'courtType', 'judge', 'defendant']);
        return view('admin.cases.show', compact('case'));
    }

    /**
     * Remove the specified case from storage.
     */
    public function destroy(CaseModel $case)
    {
        // Delete related attachments from storage
        foreach ($case->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $case->delete();
        return redirect()->route('admin.cases.index')->with('success', 'تم حذف القضية بنجاح');
    }
} 