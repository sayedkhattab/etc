@extends('admin.layouts.app')

@section('title', 'عرض المسؤول')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1>عرض المسؤول</h1>
            <p class="text-muted">عرض بيانات المسؤول {{ $admin->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> تعديل
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- معلومات الملف الشخصي -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($admin->avatar)
                        <img src="{{ asset('storage/' . $admin->avatar) }}" alt="صورة المسؤول" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                            <i class="bi bi-person-circle text-secondary" style="font-size: 80px;"></i>
                        </div>
                    @endif
                    
                    <h3 class="mt-3">{{ $admin->name }}</h3>
                    <p class="text-muted">{{ $admin->role_name ?? 'مسؤول' }}</p>
                    
                    <div class="mt-3">
                        <span class="badge bg-{{ $admin->status === 'active' ? 'success' : 'danger' }}">
                            {{ $admin->status === 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- تفاصيل المسؤول -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    معلومات المسؤول
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>الاسم:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->name }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>البريد الإلكتروني:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->email }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>اسم المستخدم:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->username }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>رقم الهاتف:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->phone ?: 'غير متوفر' }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>الدور:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->role_name ?? 'غير محدد' }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>الحالة:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $admin->status === 'active' ? 'success' : 'danger' }}">
                                {{ $admin->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>تاريخ الإنشاء:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>آخر تحديث:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->updated_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>آخر تسجيل دخول:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $admin->last_login_at ? $admin->last_login_at->format('Y-m-d H:i') : 'لم يسجل الدخول بعد' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    الصلاحيات
                </div>
                <div class="card-body">
                    @if(is_array($admin->permissions) && count($admin->permissions) > 0)
                        <div class="row">
                            @foreach($admin->permissions as $permission)
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-info">{{ $permission }}</span>
                                </div>
                            @endforeach
                        </div>
                    @elseif(is_array($admin->permissions) && in_array('*', $admin->permissions))
                        <div class="alert alert-info">
                            هذا المسؤول لديه جميع الصلاحيات.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            لا توجد صلاحيات محددة لهذا المسؤول.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 