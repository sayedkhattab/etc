@extends('admin.layouts.app')

@section('title', 'الأسئلة')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>قائمة الأسئلة {{ $assessment ? '– '.$assessment->title : '' }}</h1>
        @if($assessment)
            <a href="{{ route('admin.assessments.show', $assessment) }}" class="btn btn-secondary mt-2">
                <i class="bi bi-arrow-right"></i> العودة للاختبار
            </a>
        @endif
    </div>
    @if($assessment)
        <a href="{{ route('admin.questions.create', [], false) }}?assessment_id={{ $assessment->id }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> إضافة سؤال</a>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>النص</th>
                        <th>المحتوى المرتبط</th>
                        <th>النوع</th>
                        <th>الدرجات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $q)
                        <tr>
                            <td>{{ $q->id }}</td>
                            <td>{{ Str::limit($q->question_text, 60) }}</td>
                            <td>{{ $q->content?->title ?? '-' }}</td>
                            <td>{{ $q->questionType?->name }}</td>
                            <td>{{ $q->points }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.questions.edit', $q) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.questions.destroy', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف السؤال؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">لا توجد أسئلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $questions->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection 