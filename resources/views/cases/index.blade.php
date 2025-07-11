@extends('layouts.app')

@section('title', 'القضايا - إثبات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>القضايا</h2>
        <div>
            @if(Auth::user()->hasRole(['admin', 'judge', 'instructor']))
                <a href="{{ route('cases.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> إنشاء قضية جديدة
                </a>
            @endif
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('cases.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="ابحث عن قضية..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-end">
                        <select name="status" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                            <option value="">جميع الحالات</option>
                            @foreach($statuses ?? [] as $status)
                                <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="court_type" class="form-select" style="width: auto;" onchange="this.form.submit()">
                            <option value="">جميع المحاكم</option>
                            @foreach($courtTypes ?? [] as $courtType)
                                <option value="{{ $courtType->id }}" {{ request('court_type') == $courtType->id ? 'selected' : '' }}>
                                    {{ $courtType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان القضية</th>
                            <th>رقم القضية</th>
                            <th>المحكمة</th>
                            <th>القاضي</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cases ?? [] as $case)
                            <tr>
                                <td>{{ $case->id }}</td>
                                <td>
                                    <a href="{{ route('cases.show', $case->id) }}">{{ $case->title }}</a>
                                </td>
                                <td>{{ $case->case_number }}</td>
                                <td>{{ $case->court_type->name ?? 'غير محدد' }}</td>
                                <td>{{ $case->judge->name ?? 'غير محدد' }}</td>
                                <td>
                                    <span class="badge bg-{{ $case->status->color ?? 'secondary' }}">
                                        {{ $case->status->name ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td>{{ $case->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('cases.show', $case->id) }}" class="btn btn-sm btn-primary" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->hasRole(['admin', 'judge']) || (Auth::user()->id == $case->created_by))
                                            <a href="{{ route('cases.edit', $case->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(Auth::user()->hasRole('admin'))
                                            <form action="{{ route('cases.destroy', $case->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه القضية؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">لا توجد قضايا</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $cases->links() ?? '' }}
            </div>
        </div>
    </div>
</div>
@endsection 