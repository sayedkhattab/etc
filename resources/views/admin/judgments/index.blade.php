@extends('admin.layouts.app')

@section('title', 'الأحكام القضائية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">قائمة الأحكام</h3>
                    <a href="{{ route('admin.judgments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> إضافة حكم</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>القضية</th>
                                <th>العنوان</th>
                                <th>النوع</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($judgments as $judgment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $judgment->case?->case_number }}</td>
                                <td>{{ $judgment->title }}</td>
                                <td>{{ $judgment->judgmentType?->name }}</td>
                                <td>{{ $judgment->judgment_date?->format('Y-m-d') }}</td>
                                <td>{{ $judgment->status }}</td>
                                <td>
                                    <a href="{{ route('admin.judgments.show', $judgment) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('admin.judgments.destroy', $judgment) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $judgments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 