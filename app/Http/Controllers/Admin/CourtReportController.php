<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VirtualCourt\ActiveCase;
use App\Models\CourtSession;
use App\Models\Judgment;

class CourtReportController extends Controller
{
    /**
     * Display a listing of the court reports (dashboard statistics).
     */
    public function index()
    {
        $stats = [
            'active_cases_total'     => ActiveCase::count(),
            'active_cases_completed' => ActiveCase::where('status', 'completed')->count(),
            'upcoming_sessions'      => CourtSession::whereDate('date', '>=', now()->toDateString())->count(),
            'judgments_total'        => Judgment::count(),
        ];

        return view('admin.court-reports.index', compact('stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort(404);
    }
} 