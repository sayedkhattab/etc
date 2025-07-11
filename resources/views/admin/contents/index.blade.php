@extends('admin.layouts.app')

@section('title', 'محتوى المستوى')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>محتوى المستوى – {{ $level->title }} ({{ $course->title }})</h1>
        <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn btn-secondary mt-2">
            <i class="bi bi-arrow-right"></i> العودة لقائمة المستويات
        </a>
    </div>
    <a href="{{ route('admin.courses.levels.contents.create', [$course, $level]) }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> إضافة محتوى</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>المدة</th>
                        <th>ترتيب</th>
                        <th>مطلوب</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contents as $content)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $content->title }}</td>
                            <td>{{ $content->contentType?->name }}</td>
                            <td>{{ $content->duration ?? '-' }}</td>
                            <td>{{ $content->order }}</td>
                            <td>{{ $content->is_required ? 'نعم' : 'لا' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.courses.levels.contents.edit', [$course, $level, $content]) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.courses.levels.contents.destroy', [$course, $level, $content]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المحتوى؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا يوجد محتوى حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 