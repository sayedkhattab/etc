@extends('admin.layouts.app')

@section('title', 'مستويات الدورة: ' . $course->title)

@section('styles')
<style>
    /* Fix button group styling for RTL */
    .btn-group .btn:not(:first-child) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        margin-right: -1px;
    }
    
    .btn-group .btn:not(:last-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    /* Fix for form inside btn-group */
    .btn-group-form {
        display: inline-block;
        margin: 0;
        vertical-align: middle;
    }
    
    .btn-group-form button {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    /* Fix button group in RTL */
    .btn-group > .btn:not(:last-child):not(.dropdown-toggle),
    .btn-group > .btn-group:not(:last-child) > .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .btn-group > .btn:not(:first-child),
    .btn-group > .btn-group:not(:first-child) > .btn {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
</style>
@endsection

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>مستويات الدورة – {{ $course->title }}</h1>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary mt-2">
            <i class="bi bi-arrow-right"></i> العودة لقائمة الدورات
        </a>
    </div>
    <a href="{{ route('admin.courses.levels.create', $course) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة مستوى جديد
    </a>
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
                        <th>الوصف</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>عدد الفيديوهات</th>
                        <th>عدد الأسئلة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($levels as $level)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $level->title }}</td>
                            <td>{{ Str::limit($level->description, 50) }}</td>
                            <td>{{ $level->order }}</td>
                            <td>
                                @if($level->status === 'active')
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $videoCount = $level->contents->filter(function($content) {
                                        return $content->contentType && $content->contentType->name === 'فيديو';
                                    })->count();
                                @endphp
                                <span class="badge bg-info">{{ $videoCount }}</span>
                            </td>
                            <td>
                                @php
                                    $questionCount = $level->assessments->sum(function($assessment) {
                                        return $assessment->questions->count();
                                    });
                                @endphp
                                <span class="badge bg-warning">{{ $questionCount }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.courses.levels.contents.index', [$course, $level]) }}" class="btn btn-sm btn-info" title="إدارة المحتوى">
                                        <i class="bi bi-collection-play"></i>
                                    </a>
                                    <a href="{{ route('admin.assessments.create', [], false) }}?level_id={{ $level->id }}" class="btn btn-sm btn-warning" title="إضافة اختبار تمهيدي">
                                        <i class="bi bi-clipboard-check"></i>
                                    </a>
                                    <a href="{{ route('admin.courses.levels.edit', [$course, $level]) }}" class="btn btn-sm btn-primary" title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.levels.destroy', [$course, $level]) }}" method="POST" class="btn-group-form" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستوى؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">لا توجد مستويات حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 