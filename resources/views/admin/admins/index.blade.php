@extends('admin.layouts.app')

@section('title', 'إدارة المسؤولين')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1>إدارة المسؤولين</h1>
            <p class="text-muted">إدارة حسابات المسؤولين في النظام</p>
        </div>
        <div>
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> إضافة مسؤول جديد
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>قائمة المسؤولين</span>
            <form action="{{ route('admin.admins.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="بحث..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>اسم المستخدم</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>آخر تسجيل دخول</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>
                                    @if($admin->avatar)
                                        <img src="{{ asset('storage/' . $admin->avatar) }}" alt="صورة المسؤول" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->username }}</td>
                                <td>{{ $admin->role_name ?? 'غير محدد' }}</td>
                                <td>
                                    <span class="badge bg-{{ $admin->status === 'active' ? 'success' : 'danger' }}">
                                        {{ $admin->status === 'active' ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>{{ $admin->last_login_at ? $admin->last_login_at->format('Y-m-d H:i') : 'لم يسجل الدخول بعد' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-sm btn-info text-white" title="عرض">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-primary" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if(Auth::guard('admin')->id() !== $admin->id)
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المسؤول؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">لا يوجد مسؤولين</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $admins->links() }}
        </div>
    </div>
@endsection 