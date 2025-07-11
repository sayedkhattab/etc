@extends('layouts.app')

@section('title', 'تصحيح الأخطاء - الأسئلة التي فشل فيها الطالب')

@section('content')
<div class="container my-4">
    <h2>الأسئلة التي فشل فيها الطالب</h2>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">المحتويات المطلوبة</h3>
        </div>
        <div class="card-body">
            @if($requiredContents->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>العنوان</th>
                                <th>المستوى</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requiredContents as $content)
                                <tr>
                                    <td>{{ $content->id }}</td>
                                    <td>{{ $content->title }}</td>
                                    <td>{{ $content->level->title ?? 'غير معروف' }}</td>
                                    <td>
                                        <a href="{{ route('student.contents.show', [$content->level->course_id, $content->level_id, $content->id]) }}" class="btn btn-sm btn-primary">
                                            عرض المحتوى
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    لا توجد محتويات مطلوبة مرتبطة بالأسئلة التي فشل فيها الطالب.
                </div>
            @endif
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title mb-0">تفاصيل الأسئلة التي فشل فيها الطالب</h3>
        </div>
        <div class="card-body">
            @if($failedQuestions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>معرف السؤال</th>
                                <th>نص السؤال</th>
                                <th>المستوى</th>
                                <th>المحتوى المرتبط</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($failedQuestions as $failedQuestion)
                                <tr>
                                    <td>{{ $failedQuestion->question_id }}</td>
                                    <td>{{ $failedQuestion->question->question_text ?? 'غير معروف' }}</td>
                                    <td>{{ $failedQuestion->level->title ?? 'غير معروف' }}</td>
                                    <td>
                                        @if($failedQuestion->question && $failedQuestion->question->content)
                                            <a href="{{ route('student.contents.show', [$failedQuestion->level->course_id, $failedQuestion->level_id, $failedQuestion->question->content_id]) }}">
                                                {{ $failedQuestion->question->content->title }} (ID: {{ $failedQuestion->question->content_id }})
                                            </a>
                                        @else
                                            <span class="text-danger">غير مرتبط بمحتوى</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    لا توجد أسئلة فشل فيها الطالب.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 