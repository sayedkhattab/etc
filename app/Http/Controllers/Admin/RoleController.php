<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * عرض قائمة الأدوار.
     */
    public function index()
    {
        $roles = Role::paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * عرض نموذج إنشاء دور جديد.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * تخزين دور جديد.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        
        // تحويل الصلاحيات إلى تنسيق JSON
        if (isset($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        } else {
            $data['permissions'] = json_encode([]);
        }
        
        Role::create($data);

        return redirect()->route('admin.roles.index')->with('success', 'تم إنشاء الدور بنجاح');
    }

    /**
     * عرض الدور.
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * عرض نموذج تعديل الدور.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * تحديث الدور.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        
        // تحويل الصلاحيات إلى تنسيق JSON
        if (isset($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        } else {
            $data['permissions'] = json_encode([]);
        }
        
        $role->update($data);

        return redirect()->route('admin.roles.index')->with('success', 'تم تحديث الدور بنجاح');
    }

    /**
     * حذف الدور.
     */
    public function destroy(Role $role)
    {
        // التحقق من عدم وجود مستخدمين مرتبطين بهذا الدور
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'لا يمكن حذف هذا الدور لأنه مرتبط بمستخدمين');
        }
        
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'تم حذف الدور بنجاح');
    }
} 