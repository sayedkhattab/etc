@extends('admin.layouts.app')

@section('title', 'مشتريات القضايا')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>مشتريات القضايا</h1>
    <a href="{{ route('admin.store.purchases.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة عملية شراء جديدة
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-8">
                قائمة مشتريات القضايا
            </div>
            <div class="col-md-4">
                <form action="{{ route('admin.store.purchases.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary ms-2">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>ملف القضية</th>
                        <th>الدور</th>
                        <th>تاريخ الشراء</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($purchase->user)
                                    {{ $purchase->user->name }}
                                @else
                                    <span class="text-muted">مستخدم محذوف</span>
                                @endif
                            </td>
                            <td>
                                @if($purchase->caseFile)
                                    <a href="{{ route('admin.store.case-files.show', $purchase->caseFile->id) }}">
                                        {{ $purchase->caseFile->title }}
                                    </a>
                                @else
                                    <span class="text-muted">ملف محذوف</span>
                                @endif
                            </td>
                            <td>{{ $purchase->role }}</td>
                            <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($purchase->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                                
                                @if($purchase->is_activated_in_court)
                                    <span class="badge bg-primary">مفعل في المحكمة</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.store.purchases.show', $purchase->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.store.purchases.edit', $purchase->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.store.purchases.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه العملية؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد عمليات شراء حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $purchases->links() }}
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        إحصائيات المشتريات
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي المشتريات</h5>
                        <p class="card-text display-6">{{ $statistics['total'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">المشتريات النشطة</h5>
                        <p class="card-text display-6">{{ $statistics['active'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">دور المدعي</h5>
                        <p class="card-text display-6">{{ $statistics['plaintiff'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">دور المدعى عليه</h5>
                        <p class="card-text display-6">{{ $statistics['defendant'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 