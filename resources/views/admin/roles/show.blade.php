@extends('admin.layouts.app')

@section('title', 'عرض تفاصيل الدور')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل الدور: {{ $role->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-right"></i> العودة للقائمة
                        </a>
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">اسم الدور:</label>
                                <p>{{ $role->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">الوصف:</label>
                                <p>{{ $role->description ?? 'لا يوجد وصف' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">تاريخ الإنشاء:</label>
                                <p>{{ $role->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">آخر تحديث:</label>
                                <p>{{ $role->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">عدد المستخدمين المرتبطين:</label>
                                <p>{{ $role->users()->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>الصلاحيات</h4>
                            <hr>
                        </div>
                    </div>

                    @php
                        $permissions = is_string($role->permissions) ? json_decode($role->permissions, true) : ($role->permissions ?? []);
                    @endphp

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">إدارة المستخدمين</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li>
                                            <i class="{{ in_array('view_users', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            عرض المستخدمين
                                        </li>
                                        <li>
                                            <i class="{{ in_array('create_users', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            إنشاء مستخدمين
                                        </li>
                                        <li>
                                            <i class="{{ in_array('edit_users', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            تعديل المستخدمين
                                        </li>
                                        <li>
                                            <i class="{{ in_array('delete_users', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            حذف المستخدمين
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">إدارة الأدوار</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li>
                                            <i class="{{ in_array('view_roles', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            عرض الأدوار
                                        </li>
                                        <li>
                                            <i class="{{ in_array('create_roles', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            إنشاء أدوار
                                        </li>
                                        <li>
                                            <i class="{{ in_array('edit_roles', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            تعديل الأدوار
                                        </li>
                                        <li>
                                            <i class="{{ in_array('delete_roles', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            حذف الأدوار
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">إعدادات النظام</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li>
                                            <i class="{{ in_array('view_settings', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            عرض الإعدادات
                                        </li>
                                        <li>
                                            <i class="{{ in_array('edit_settings', $permissions) ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                                            تعديل الإعدادات
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($role->users()->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>المستخدمين المرتبطين بهذا الدور</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users()->paginate(10) as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-pencil"></i> تعديل
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 