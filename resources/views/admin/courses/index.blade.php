@extends('admin.layouts.app')

@section('title', 'الدورات التعليمية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">قائمة الدورات</h3>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus"></i> إضافة دورة
                    </a>
                </div>
                <div class="card-body">
                    <style>
                        .btn-group .btn-danger {
                            border-top-right-radius: 0 !important;
                            border-bottom-right-radius: 0 !important;
                        }
                    </style>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>التصنيف</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->category?->name }}</td>
                                    <td>{{ $course->price }}</td>
                                    <td>{{ $course->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-info" title="عرض"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-warning" title="تعديل"><i class="bi bi-pencil"></i></a>
                                            <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn btn-sm btn-success" title="المستويات"><i class="bi bi-layers"></i></a>
                                            <a href="{{ route('admin.assessments.index', ['course_id' => $course->id]) }}" class="btn btn-sm btn-primary" title="الاختبارات"><i class="bi bi-clipboard-check"></i></a>
                                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="حذف"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 