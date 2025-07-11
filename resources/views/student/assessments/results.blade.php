@extends('layouts.app')

@section('title', 'نتائج التقييم - ' . ($assessment->title ?? 'تقييم'))

@section('content')
<div class="container my-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.courses.show', $course->id) }}">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">نتائج التقييم</li>
        </ol>
    </nav>

    <h2 class="mb-3">نتائج التقييم: {{ $assessment->title }}</h2>

    <div class="alert alert-{{ $attempt->passed ? 'success' : 'danger' }}">
        <h4 class="alert-heading">{{ $attempt->passed ? 'مبروك! اجتزت الاختبار.' : 'للأسف، لم تجتز الاختبار.' }}</h4>
        <p class="mb-0">درجتك: {{ number_format($attempt->score, 2) }}٪ — الحد الأدنى للنجاح {{ $assessment->passing_score }}٪</p>
    </div>

    <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-primary mt-3">العودة للدورة</a>
</div>
@endsection 