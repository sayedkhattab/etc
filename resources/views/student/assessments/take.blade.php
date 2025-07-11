@extends('layouts.app')

@section('title', 'اختبار المراجعة - ' . ($assessment->title ?? 'تقييم'))

@section('content')
<div class="container my-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.courses.show', $course->id) }}">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $assessment->title }}</li>
        </ol>
    </nav>

    <h2 class="mb-3">{{ $assessment->title }}</h2>
    <p class="text-muted mb-4">الدرجة المطلوبة للنجاح: {{ $assessment->passing_score }}٪ &nbsp;|&nbsp; المحاولة رقم {{ $attempt->attempt_number }} من {{ $assessment->attempts_allowed }}</p>

    <form action="{{ route('student.assessments.submit', [$course->id, $level->id, $assessment->id, $attempt->id]) }}" method="POST">
        @csrf
        @foreach($questions as $qIndex => $question)
            <div class="card mb-3">
                <div class="card-header">
                    سؤال {{ $qIndex + 1 }}: {{ $question->question_text }}
                </div>
                <div class="card-body">
                    @foreach($question->options as $opt)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="{{ $opt->id }}" id="q{{ $question->id }}_{{ $opt->id }}" required>
                            <label class="form-check-label" for="q{{ $question->id }}_{{ $opt->id }}">
                                {{ $opt->option_text }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">إرسال الإجابات</button>
    </form>
</div>
@endsection 