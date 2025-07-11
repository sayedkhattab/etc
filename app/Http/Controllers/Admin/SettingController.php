<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display general settings form.
     */
    public function index()
    {
        $settingsKeys = ['site_name', 'contact_email', 'contact_phone'];
        $settings = [];
        foreach ($settingsKeys as $key) {
            $settings[$key] = SystemSetting::getValue($key, '');
        }
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'      => 'required|string|max:255',
            'contact_email'  => 'required|email',
            'contact_phone'  => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::setValue($key, $value, 'general');
        }

        return redirect()->route('admin.settings.general')->with('success', 'تم تحديث الإعدادات بنجاح');
    }
} 