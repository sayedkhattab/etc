@extends('layouts.app')

@section('title', 'إكمال الدورة - ' . ($course->title ?? 'دورة'))

@section('content')
<div class="container my-5 text-center">
    <h2 class="mb-4">تهانينا! لقد أكملت دورة {{ $course->title }}</h2>

    @if($certificate)
        <p class="lead">تم إصدار شهادتك بنجاح.</p>
        <a href="{{ route('certificates.download', $certificate->id) }}" class="btn btn-success btn-lg">
            <i class="fas fa-download me-2"></i> تنزيل الشهادة
        </a>
    @else
        <p class="lead">يمكنك الآن طلب شهادتك.</p>
        <form action="{{ route('courses.certificates.store', $course->id) }}" method="POST" class="d-inline-block">
            @csrf
            <input type="hidden" name="student_id" value="{{ auth()->id() }}">
            <input type="hidden" name="issue_date" value="{{ now()->toDateString() }}">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-certificate me-2"></i> إصدار الشهادة
            </button>
        </form>
    @endif

    <div class="mt-5">
        <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-outline-secondary">العودة للدورة</a>
        <a href="{{ route('student.certificates') }}" class="btn btn-outline-primary">شهاداتي</a>
    </div>
</div>
@endsection 