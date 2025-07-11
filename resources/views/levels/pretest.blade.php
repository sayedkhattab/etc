@extends('layouts.app')

@section('title', 'اختبار تحديد المستوى')

@section('content')
<div class="container my-4">
    <h2 class="mb-3">اختبار تحديد المستوى - {{ $level->title }}</h2>
    
    @if($questions->isEmpty())
        <div class="alert alert-warning">
            <h4>لا توجد أسئلة متاحة</h4>
            <p>لم يتم العثور على أسئلة لهذا الاختبار. يرجى التواصل مع مسؤول النظام.</p>
        </div>
    @else
        <form action="{{ route('levels.pretest.submit', [$course, $level]) }}" method="POST">
            @csrf
            <div class="mb-3">
                <p><strong>عدد الأسئلة:</strong> {{ $questions->count() }}</p>
            </div>
            
            @foreach($questions as $index => $question)
                <div class="card mb-3">
                    <div class="card-header">
                        سؤال {{ $index + 1 }}: {{ $question->question_text }}
                    </div>
                    <div class="card-body">
                        @if($question->options->isEmpty())
                            <div class="alert alert-danger">
                                لا توجد خيارات لهذا السؤال!
                            </div>
                        @else
                            @foreach($question->options as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" id="q{{ $question->id }}_{{ $option->id }}" required>
                                    <label class="form-check-label" for="q{{ $question->id }}_{{ $option->id }}">
                                        {{ $option->option_text }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">إرسال الإجابات</button>
        </form>
    @endif
    
    <!-- معلومات تصحيح الأخطاء -->
    @if(config('app.debug'))
        <div class="mt-5">
            <h4>معلومات تصحيح الأخطاء</h4>
            <div class="card">
                <div class="card-body">
                    <h5>معلومات التقييم</h5>
                    <ul>
                        <li>معرف التقييم: {{ $preTest->id ?? 'غير متوفر' }}</li>
                        <li>عنوان التقييم: {{ $preTest->title ?? 'غير متوفر' }}</li>
                        <li>نوع التقييم: {{ $preTest->assessmentType->name ?? 'غير متوفر' }}</li>
                        <li>هل هو اختبار تحديد مستوى: {{ $preTest->is_pretest ? 'نعم' : 'لا' }}</li>
                    </ul>
                    
                    <h5>معلومات الأسئلة</h5>
                    <p>عدد الأسئلة: {{ $questions->count() }}</p>
                    @if($questions->isNotEmpty())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نص السؤال</th>
                                    <th>عدد الخيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $index => $question)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $question->question_text }}</td>
                                        <td>{{ $question->options->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>لا توجد أسئلة!</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 