@extends('admin.layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
    <div class="page-header">
        <h1>الملف الشخصي</h1>
        <p class="text-muted">إدارة معلوماتك الشخصية وتغيير كلمة المرور</p>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- معلومات الملف الشخصي -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($admin->avatar)
                        <img src="{{ asset('storage/' . $admin->avatar) }}" alt="صورة الملف الشخصي" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                            <i class="bi bi-person-circle text-secondary" style="font-size: 80px;"></i>
                        </div>
                    @endif
                    
                    <h3 class="mt-3">{{ $admin->name }}</h3>
                    <p class="text-muted">{{ $admin->role_name ?? 'مسؤول' }}</p>
                    
                    <div class="mt-3">
                        <p><i class="bi bi-envelope me-2"></i> {{ $admin->email }}</p>
                        <p><i class="bi bi-person me-2"></i> {{ $admin->username }}</p>
                        @if($admin->phone)
                            <p><i class="bi bi-telephone me-2"></i> {{ $admin->phone }}</p>
                        @endif
                    </div>
                    
                    <div class="mt-3">
                        <span class="badge bg-{{ $admin->status === 'active' ? 'success' : 'danger' }}">
                            {{ $admin->status === 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-muted">آخر تسجيل دخول: {{ $admin->last_login_at ? $admin->last_login_at->format('Y-m-d H:i') : 'غير متوفر' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- تعديل الملف الشخصي -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    تعديل الملف الشخصي
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">الاسم</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">اسم المستخدم</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $admin->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $admin->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="avatar" class="form-label">الصورة الشخصية</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                            <small class="form-text text-muted">اترك هذا الحقل فارغًا إذا كنت لا ترغب في تغيير الصورة الشخصية.</small>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">تغيير كلمة المرور</h5>
                        <p class="text-muted mb-3">اترك هذه الحقول فارغة إذا كنت لا ترغب في تغيير كلمة المرور.</p>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 