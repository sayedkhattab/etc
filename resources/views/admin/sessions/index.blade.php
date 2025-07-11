@extends('admin.layouts.app')

@section('title', 'الجلسات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">قائمة الجلسات</h3>
                    <a href="{{ route('admin.sessions.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> إضافة جلسة</a>
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
                            @foreach($sessions as $session)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $session->case?->case_number }}</td>
                                <td>{{ $session->title }}</td>
                                <td>{{ $session->sessionType?->name }}</td>
                                <td>{{ $session->date_time?->format('Y-m-d H:i') }}</td>
                                <td>{{ $session->status?->name }}</td>
                                <td>
                                    <a href="{{ route('admin.sessions.show', $session) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('admin.sessions.destroy', $session) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 