@extends('layouts.app')

@section('title', 'نتيجة اختبار المستوى')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">نتيجة اختبار المستوى: {{ $level->title }}</h2>

    <div class="card mx-auto" style="max-width: 800px;">
        <div class="card-header {{ $passed ? 'bg-success' : 'bg-warning' }} text-white">
            <h3 class="mb-0">النتيجة: {{ $score }}% ({{ $correctAnswers }}/{{ $totalQuestions }})</h3>
        </div>
        <div class="card-body">
            @if($passed)
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> أحسنت! لقد اجتزت اختبار تحديد المستوى بنجاح.
                    <p class="mt-2">يمكنك الآن مشاهدة محتوى المستوى أو تخطيه حسب رغبتك.</p>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> للأسف، لم تتمكن من اجتياز اختبار تحديد المستوى.
                    <p class="mt-2 fw-bold">يجب عليك مشاهدة المحتويات المرتبطة بالأسئلة التي أخطأت فيها.</p>
                </div>
                
                @if(isset($failedQuestionsWithContent) && count($failedQuestionsWithContent) > 0)
                    <div class="card mt-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">المحتويات المطلوب مشاهدتها</h5>
                        </div>
                        <div class="card-body">
                            <p class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                يجب عليك مشاهدة المحتويات التالية بالكامل لتحسين فهمك للمواضيع التي أخطأت فيها
                            </p>
                            
                            <div class="list-group mt-3">
                                @foreach($failedQuestionsWithContent as $item)
                                    <a href="{{ route('student.contents.show', [$course->id, $level->id, $item['content_id']]) }}" 
                                       class="list-group-item list-group-item-action list-group-item-danger">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1">{{ $item['content_title'] }}</h5>
                                                <small>هذا المحتوى مرتبط بسؤال أخطأت في إجابته</small>
                                            </div>
                                            <span class="btn btn-sm btn-danger">
                                                <i class="fas fa-play-circle me-1"></i> مشاهدة الآن
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        لم يتم العثور على محتويات مرتبطة بالأسئلة التي أخطأت فيها. يرجى مشاهدة جميع محتويات المستوى.
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center gap-3">
        @if($passed)
            <a href="{{ route('student.levels.show', [$course, $level]) }}" class="btn btn-outline-primary">
                <i class="fas fa-list me-2"></i> عرض محتويات المستوى
            </a>
            <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-success">
                <i class="fas fa-forward me-2"></i> الانتقال إلى لوحة الدورة
            </a>
        @else
            @if(isset($firstContent))
                <a href="{{ route('student.contents.show', [$course->id, $level->id, $firstContent->id]) }}" class="btn btn-danger btn-lg">
                    <i class="fas fa-play-circle me-2"></i> بدء مشاهدة المحتوى المطلوب
                </a>
            @endif
            <a href="{{ route('student.levels.show', [$course, $level]) }}" class="btn btn-primary">
                <i class="fas fa-list me-2"></i> عرض جميع محتويات المستوى
            </a>
        @endif
    </div>
    
    <div class="mt-4 text-center">
        <a href="{{ route('debug.failed-questions') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-bug me-1"></i> عرض تفاصيل الأسئلة التي أخطأت فيها
        </a>
    </div>
</div>
@endsection 