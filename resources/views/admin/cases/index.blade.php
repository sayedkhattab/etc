@extends('admin.layouts.app')

@section('title', 'القضايا')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">قائمة القضايا</h3>
                    <a href="{{ route('admin.cases.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> إضافة قضية</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم القضية</th>
                                <th>القاضي</th>
                                <th>الحالة</th>
                                <th>تاريخ البدء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cases as $case)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $case->case_number }}</td>
                                <td>{{ $case->judge?->name }}</td>
                                <td>{{ $case->status?->name }}</td>
                                <td>{{ $case->start_date?->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.cases.show', $case) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('admin.cases.destroy', $case) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $cases->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 