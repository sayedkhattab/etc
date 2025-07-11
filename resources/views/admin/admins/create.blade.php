@extends('admin.layouts.app')

@section('title', 'إضافة مسؤول جديد')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1>إضافة مسؤول جديد</h1>
            <p class="text-muted">إنشاء حساب مسؤول جديد في النظام</p>
        </div>
        <div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            بيانات المسؤول الجديد
        </div>
        <div class="card-body">
            <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="role_id" class="form-label">الدور <span class="text-danger">*</span></label>
                        <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                            <option value="">اختر الدور</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="avatar" class="form-label">الصورة الشخصية</label>
                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                    @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label">الصلاحيات</label>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_admins" value="manage_admins" {{ is_array(old('permissions')) && in_array('manage_admins', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_admins">إدارة المسؤولين</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_users" value="manage_users" {{ is_array(old('permissions')) && in_array('manage_users', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_users">إدارة المستخدمين</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_roles" value="manage_roles" {{ is_array(old('permissions')) && in_array('manage_roles', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_roles">إدارة الأدوار</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_courses" value="manage_courses" {{ is_array(old('permissions')) && in_array('manage_courses', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_courses">إدارة الدورات</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_content" value="manage_content" {{ is_array(old('permissions')) && in_array('manage_content', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_content">إدارة المحتوى</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_assessments" value="manage_assessments" {{ is_array(old('permissions')) && in_array('manage_assessments', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_assessments">إدارة التقييمات</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_certificates" value="manage_certificates" {{ is_array(old('permissions')) && in_array('manage_certificates', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_certificates">إدارة الشهادات</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_cases" value="manage_cases" {{ is_array(old('permissions')) && in_array('manage_cases', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_cases">إدارة القضايا</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_sessions" value="manage_sessions" {{ is_array(old('permissions')) && in_array('manage_sessions', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_sessions">إدارة الجلسات</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_judgments" value="manage_judgments" {{ is_array(old('permissions')) && in_array('manage_judgments', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_judgments">إدارة الأحكام</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="view_reports" value="view_reports" {{ is_array(old('permissions')) && in_array('view_reports', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="view_reports">عرض التقارير</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="manage_settings" value="manage_settings" {{ is_array(old('permissions')) && in_array('manage_settings', old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_settings">إدارة الإعدادات</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">إضافة المسؤول</button>
                </div>
            </form>
        </div>
    </div>
@endsection 