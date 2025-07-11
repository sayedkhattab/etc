<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the admins.
     */
    public function index()
    {
        $admins = Admin::with('role')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['password', 'password_confirmation', 'avatar', 'permissions']);
        $data['password'] = Hash::make($request->password);
        
        // معالجة الصلاحيات
        $data['permissions'] = $request->permissions ?? [];
        
        // معالجة الصورة الشخصية
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('admins/avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        Admin::create($data);

        return redirect()->route('admin.admins.index')->with('success', 'تم إنشاء المسؤول بنجاح');
    }

    /**
     * Display the specified admin.
     */
    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(Admin $admin)
    {
        $roles = Role::all();
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['password', 'password_confirmation', 'avatar', 'permissions']);
        
        // تحديث كلمة المرور إذا تم تقديمها
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        // معالجة الصلاحيات
        $data['permissions'] = $request->permissions ?? [];
        
        // معالجة الصورة الشخصية
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('admins/avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'تم تحديث المسؤول بنجاح');
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(Admin $admin)
    {
        // منع حذف المسؤول الحالي
        if (Auth::guard('admin')->id() === $admin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'لا يمكنك حذف حسابك الحالي');
        }

        // حذف الصورة الشخصية إذا كانت موجودة
        if ($admin->avatar) {
            Storage::disk('public')->delete($admin->avatar);
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'تم حذف المسؤول بنجاح');
    }

    /**
     * Display the admin profile.
     */
    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    /**
     * Update the admin profile.
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // التحقق من كلمة المرور الحالية
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])->withInput();
            }
        }

        $data = $request->only(['name', 'email', 'username', 'phone']);
        
        // تحديث كلمة المرور إذا تم تقديمها
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        // معالجة الصورة الشخصية
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('admins/avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $admin->update($data);

        return redirect()->route('admin.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
