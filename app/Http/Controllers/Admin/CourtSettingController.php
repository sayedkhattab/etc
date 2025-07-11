<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VirtualCourt\CourtSetting;

class CourtSettingController extends Controller
{
    /**
     * Display a listing of the court settings.
     */
    public function index()
    {
        $settings = CourtSetting::orderBy('group')->orderBy('key')->paginate(20);
        return view('admin.court-settings.index', compact('settings'));
    }

    /**
     * Show the form for editing the specified setting.
     */
    public function edit($id)
    {
        $setting = CourtSetting::findOrFail($id);
        return view('admin.court-settings.edit', compact('setting'));
    }

    /**
     * Update the specified setting in storage.
     */
    public function update(Request $request, $id)
    {
        $setting = CourtSetting::findOrFail($id);

        $request->validate([
            'value' => 'nullable|string',
        ]);

        $setting->update(['value' => $request->input('value')]);

        return redirect()->route('admin.court-settings.index')->with('success', 'تم تحديث الإعداد بنجاح');
    }

    // Unused resource methods
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function show($id) { abort(404); }
    public function destroy($id) { abort(404); }
} 