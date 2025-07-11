@extends('admin.layouts.app')

@section('title', 'تعديل الدور')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل الدور: {{ $role->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">اسم الدور <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">الوصف</label>
                                    <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $role->description) }}">
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
                            $permissions = old('permissions', $role->permissions ?? []);
                            if (is_string($permissions)) {
                                $permissions = json_decode($permissions, true) ?? [];
                            }
                        @endphp

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">إدارة المستخدمين</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="view_users" id="view_users" class="form-check-input" {{ in_array('view_users', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="view_users">عرض المستخدمين</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="create_users" id="create_users" class="form-check-input" {{ in_array('create_users', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="create_users">إنشاء مستخدمين</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="edit_users" id="edit_users" class="form-check-input" {{ in_array('edit_users', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_users">تعديل المستخدمين</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="delete_users" id="delete_users" class="form-check-input" {{ in_array('delete_users', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="delete_users">حذف المستخدمين</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">إدارة الأدوار</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="view_roles" id="view_roles" class="form-check-input" {{ in_array('view_roles', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="view_roles">عرض الأدوار</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="create_roles" id="create_roles" class="form-check-input" {{ in_array('create_roles', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="create_roles">إنشاء أدوار</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="edit_roles" id="edit_roles" class="form-check-input" {{ in_array('edit_roles', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_roles">تعديل الأدوار</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="delete_roles" id="delete_roles" class="form-check-input" {{ in_array('delete_roles', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="delete_roles">حذف الأدوار</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">إعدادات النظام</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="view_settings" id="view_settings" class="form-check-input" {{ in_array('view_settings', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="view_settings">عرض الإعدادات</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="edit_settings" id="edit_settings" class="form-check-input" {{ in_array('edit_settings', $permissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_settings">تعديل الإعدادات</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 