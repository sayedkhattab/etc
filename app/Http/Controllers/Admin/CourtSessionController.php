<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourtSession;
use App\Models\CaseModel;
use App\Models\SessionType;
use App\Models\SessionStatus;
use Illuminate\Http\Request;

class CourtSessionController extends Controller
{
    /**
     * Display a listing of court sessions.
     */
    public function index()
    {
        $sessions = CourtSession::with(['case', 'sessionType', 'status'])->orderByDesc('date_time')->paginate(20);
        return view('admin.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        $cases         = CaseModel::with('judge')->get();
        $sessionTypes  = SessionType::all();
        $sessionStatus = SessionStatus::all();

        return view('admin.sessions.create', compact('cases', 'sessionTypes', 'sessionStatus'));
    }

    /**
     * Store a newly created session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id'        => 'required|exists:cases,id',
            'session_type_id'=> 'required|exists:session_types,id',
            'title'          => 'required|string|max:255',
            'date_time'      => 'required|date|after_or_equal:now',
            'duration'       => 'nullable|integer|min:1',
            'location'       => 'nullable|string|max:255',
            'status_id'      => 'required|exists:session_statuses,id',
        ]);

        $session = CourtSession::create($validated);

        return redirect()->route('admin.sessions.show', $session)->with('success', 'تم إنشاء الجلسة بنجاح');
    }

    /**
     * Display the specified session.
     */
    public function show(CourtSession $session)
    {
        $session->load(['case', 'sessionType', 'status']);
        return view('admin.sessions.show', compact('session'));
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(CourtSession $session)
    {
        $cases         = CaseModel::all();
        $sessionTypes  = SessionType::all();
        $sessionStatus = SessionStatus::all();

        return view('admin.sessions.edit', compact('session', 'cases', 'sessionTypes', 'sessionStatus'));
    }

    /**
     * Update the specified session.
     */
    public function update(Request $request, CourtSession $session)
    {
        $validated = $request->validate([
            'case_id'        => 'required|exists:cases,id',
            'session_type_id'=> 'required|exists:session_types,id',
            'title'          => 'required|string|max:255',
            'date_time'      => 'required|date',
            'duration'       => 'nullable|integer|min:1',
            'location'       => 'nullable|string|max:255',
            'status_id'      => 'required|exists:session_statuses,id',
        ]);

        $session->update($validated);

        return redirect()->route('admin.sessions.show', $session)->with('success', 'تم تحديث بيانات الجلسة');
    }

    /**
     * Remove the specified session.
     */
    public function destroy(CourtSession $session)
    {
        $session->delete();
        return redirect()->route('admin.sessions.index')->with('success', 'تم حذف الجلسة');
    }
} 