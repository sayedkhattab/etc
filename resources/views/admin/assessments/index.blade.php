@extends('admin.layouts.app')

@section('title', isset($course) ? 'اختبارات ' . $course->title : 'التقييمات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        @if(request()->has('course_id'))
                            اختبارات دورة: {{ $assessments->first()?->level?->course?->title ?? 'الدورة' }}
                            <a href="{{ route('admin.courses.show', request()->course_id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="bi bi-arrow-right"></i> العودة للدورة
                            </a>
                        @else
                            قائمة التقييمات
                        @endif
                    </h3>
                    <a href="{{ route('admin.assessments.create', request()->has('course_id') ? ['course_id' => request()->course_id] : []) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> إضافة تقييم</a>
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
                                <th>المستوى</th>
                                @if(!request()->has('course_id'))
                                <th>الدورة</th>
                                @endif
                                <th>النوع</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assessments as $ass)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ass->title }}</td>
                                    <td>{{ $ass->level?->title }}</td>
                                    @if(!request()->has('course_id'))
                                    <td>{{ $ass->level?->course?->title }}</td>
                                    @endif
                                    <td>{{ $ass->assessmentType?->name }}</td>
                                    <td>{{ $ass->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.assessments.show', $ass) }}" class="btn btn-sm btn-info" title="عرض"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('admin.assessments.edit', $ass) }}" class="btn btn-sm btn-warning" title="تعديل"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('admin.assessments.destroy', $ass) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
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
                    {{ $assessments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 