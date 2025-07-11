@extends('admin.layouts.app')

@section('title', 'ملفات القضايا')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>ملفات القضايا</h1>
    <a href="{{ route('admin.store.case-files.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة ملف قضية جديد
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-8">
                قائمة ملفات القضايا
            </div>
            <div class="col-md-4">
                <form action="{{ route('admin.store.case-files.index') }}" method="GET" class="d-flex">
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
                        <th>رقم القضية</th>
                        <th>العنوان</th>
                        <th>الفئة</th>
                        <th>نوع القضية</th>
                        <th>السعر</th>
                        <th>الصعوبة</th>
                        <th>الحالة</th>
                        <th>المشتريات</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($caseFiles as $caseFile)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $caseFile->case_number ?: 'غير محدد' }}</td>
                            <td>
                                @if($caseFile->thumbnail)
                                    <img src="{{ asset('storage/' . $caseFile->thumbnail) }}" alt="{{ $caseFile->title }}" width="30" height="30" class="me-2">
                                @endif
                                {{ $caseFile->title }}
                            </td>
                            <td>{{ $caseFile->category->name }}</td>
                            <td>
                                @if($caseFile->case_type == 'مدعي')
                                    <span class="badge bg-primary">مدعي</span>
                                @elseif($caseFile->case_type == 'مدعى عليه')
                                    <span class="badge bg-info">مدعى عليه</span>
                                @else
                                    <span class="badge bg-secondary">غير محدد</span>
                                @endif
                            </td>
                            <td>{{ number_format($caseFile->price, 2) }} ريال</td>
                            <td>{{ $caseFile->difficulty }}</td>
                            <td>
                                @if($caseFile->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                                
                                @if($caseFile->is_featured)
                                    <span class="badge bg-warning">مميز</span>
                                @endif
                            </td>
                            <td>{{ $caseFile->purchases_count }}</td>
                            <td>{{ $caseFile->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.store.case-files.show', $caseFile->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.store.case-files.edit', $caseFile->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.store.case-files.destroy', $caseFile->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الملف؟')">
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
                            <td colspan="11" class="text-center">لا توجد ملفات قضايا حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $caseFiles->links() }}
        </div>
    </div>
</div>
@endsection 