@extends('admin.layouts.app')

@section('title', 'تفاصيل مستوى')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>المستوى: {{ $level->title }}</h1>
    <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <p><strong>الوصف:</strong> {{ $level->description }}</p>
        <p><strong>الترتيب:</strong> {{ $level->order }}</p>
        <p><strong>الحالة:</strong> {{ $level->status }}</p>
        <a href="{{ route('admin.courses.levels.edit', [$course, $level]) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> تعديل</a>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h2 class="h4 mb-0">محتوى المستوى</h2>
    <a href="{{ route('admin.courses.levels.contents.create', [$course, $level]) }}" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> إضافة محتوى</a>
</div>

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
                    @forelse($level->contents as $content)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $content->title }}</td>
                            <td>{{ $content->contentType?->name }}</td>
                            <td>{{ $content->duration ?? '-' }}</td>
                            <td>{{ $content->order }}</td>
                            <td>{{ $content->is_required ? 'نعم' : 'لا' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.courses.levels.contents.edit', [$course, $level, $content]) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.courses.levels.contents.destroy', [$course, $level, $content]) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا المحتوى؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا يوجد محتوى بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 