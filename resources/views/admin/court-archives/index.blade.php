@extends('admin.layouts.app')

@section('title', 'أرشيف القضايا')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>أرشيف القضايا</h1>
    <a href="{{ route('admin.court-archives.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة قضية جديدة
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>بحث متقدم</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.court-archives.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="case_number" class="form-label">رقم القضية</label>
                <input type="text" class="form-control" id="case_number" name="case_number" value="{{ request('case_number') }}">
            </div>
            <div class="col-md-3">
                <label for="title" class="form-label">عنوان القضية</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ request('title') }}">
            </div>
            <div class="col-md-3">
                <label for="case_type" class="form-label">نوع القضية</label>
                <select class="form-select" id="case_type" name="case_type">
                    <option value="">-- الكل --</option>
                    <option value="مدني" {{ request('case_type') == 'مدني' ? 'selected' : '' }}>مدني</option>
                    <option value="جنائي" {{ request('case_type') == 'جنائي' ? 'selected' : '' }}>جنائي</option>
                    <option value="تجاري" {{ request('case_type') == 'تجاري' ? 'selected' : '' }}>تجاري</option>
                    <option value="إداري" {{ request('case_type') == 'إداري' ? 'selected' : '' }}>إداري</option>
                    <option value="أحوال شخصية" {{ request('case_type') == 'أحوال شخصية' ? 'selected' : '' }}>أحوال شخصية</option>
                    <option value="عمالي" {{ request('case_type') == 'عمالي' ? 'selected' : '' }}>عمالي</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="created_at" class="form-label">تاريخ الإنشاء</label>
                <input type="date" class="form-control" id="created_at" name="created_at" value="{{ request('created_at') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> بحث
                </button>
                <a href="{{ route('admin.court-archives.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> إعادة تعيين
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>قائمة القضايا ({{ $caseFiles->total() }})</h5>
    </div>
    <div class="card-body">
        @if($caseFiles->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم القضية</th>
                            <th>العنوان</th>
                            <th>نوع القضية</th>
                            <th>المدعي</th>
                            <th>المدعى عليه</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($caseFiles as $caseFile)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $caseFile->case_number }}</td>
                                <td>{{ $caseFile->title }}</td>
                                <td>{{ $caseFile->case_type }}</td>
                                <td>{{ $caseFile->plaintiff }}</td>
                                <td>{{ $caseFile->defendant }}</td>
                                <td>
                                    @if($caseFile->status == 'جاري')
                                        <span class="badge bg-primary">جاري</span>
                                    @elseif($caseFile->status == 'مكتمل')
                                        <span class="badge bg-success">مكتمل</span>
                                    @elseif($caseFile->status == 'مؤجل')
                                        <span class="badge bg-warning">مؤجل</span>
                                    @elseif($caseFile->status == 'مغلق')
                                        <span class="badge bg-secondary">مغلق</span>
                                    @endif
                                </td>
                                <td>{{ $caseFile->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.court-archives.show', $caseFile->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.court-archives.edit', $caseFile->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.court-archives.destroy', $caseFile->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه القضية؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $caseFiles->appends(request()->query())->links() }}
            </div>
        @else
            <div class="alert alert-info">
                لا توجد قضايا في الأرشيف حالياً
            </div>
        @endif
    </div>
</div>
@endsection 