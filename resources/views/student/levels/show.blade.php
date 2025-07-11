@extends('layouts.app')

@section('title', $level->title . ' - ' . $course->title)

@section('content')
<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses.catalog') }}">الدورات</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.courses.show', $course) }}">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $level->title }}</li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-header">
            <h2 class="card-title mb-0">{{ $level->title }}</h2>
        </div>
        <div class="card-body">
            @if($level->description)
                <p>{{ $level->description }}</p>
            @endif

            <div class="mt-3">
                <h5>معلومات المستوى:</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        الترتيب
                        <span class="badge bg-primary rounded-pill">{{ $level->order }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if($level->hasPreTest())
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">اختبار تحديد المستوى</h3>
            </div>
            <div class="card-body">
                @if($level->hasCompletedPreTest(auth()->id()))
                    @if($level->hasPassedPreTest(auth()->id()))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> لقد اجتزت اختبار تحديد المستوى بنجاح. يمكنك مشاهدة المحتوى أو تخطيه.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> لقد أكملت اختبار تحديد المستوى ولكن يجب عليك مشاهدة المحتوى لتحسين مستواك.
                        </div>
                        
                        @php
                            // عرض المحتويات المطلوبة للطالب بناءً على الأسئلة التي رسب فيها
                            $failedQuestionContentIds = \App\Models\Question::whereHas('failedQuestions', function($query) {
                                $query->where('student_id', auth()->id())
                                    ->where('resolved', false);
                            })
                            ->whereNotNull('content_id')
                            ->pluck('content_id')
                            ->unique();
                            
                            $requiredContents = \App\Models\CourseContent::whereIn('id', $failedQuestionContentIds)->get();
                        @endphp
                        
                        @if($requiredContents->isNotEmpty())
                            <div class="alert alert-danger mt-3">
                                <h5><i class="fas fa-exclamation-circle me-2"></i> المحتويات المطلوب مشاهدتها:</h5>
                                <p>يجب عليك مشاهدة المحتويات التالية بالكامل قبل المتابعة:</p>
                                <div class="list-group mt-3">
                                    @foreach($requiredContents as $reqContent)
                                        <a href="{{ route('student.contents.show', [$course, $level, $reqContent]) }}" 
                                           class="list-group-item list-group-item-action list-group-item-danger">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1">{{ $reqContent->title }}</h5>
                                                    <small>مطلوب مشاهدته بالكامل</small>
                                                </div>
                                                <span class="btn btn-sm btn-danger">
                                                    <i class="fas fa-play-circle me-1"></i> مشاهدة الآن
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                لم يتم العثور على محتويات مرتبطة بالأسئلة التي أخطأت فيها. يرجى مشاهدة جميع محتويات المستوى.
                            </div>
                        @endif
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> <strong>يفضل إكمال اختبار تحديد المستوى قبل الوصول إلى محتوى هذا المستوى</strong>
                        <p>سيساعدك هذا الاختبار في تحديد المحتوى المناسب لك، ولكن يمكنك الوصول إلى جميع المحتويات في أي وقت.</p>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('levels.pretest', [$course, $level]) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-play-circle me-2"></i> ابدأ اختبار تحديد المستوى
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">محتويات المستوى</h3>
        </div>
        <div class="card-body">
            @if($level->contents->count() > 0)
                <div class="list-group">
                    @php
                        // الحصول على محتويات مرتبطة بالأسئلة التي رسب فيها الطالب
                        $failedQuestionContentIds = \App\Models\Question::whereHas('failedQuestions', function($query) {
                            $query->where('student_id', auth()->id())
                                ->where('resolved', false);
                        })
                        ->whereNotNull('content_id')
                        ->pluck('content_id')
                        ->unique()
                        ->toArray();
                    @endphp
                    
                    @foreach($level->contents as $content)
                        @php
                            $isRequired = in_array($content->id, $failedQuestionContentIds);
                            // إزالة شرط قفل المحتوى
                            $isLocked = false;
                            $showAsRequired = $level->hasPreTest() && !$level->hasPassedPreTest(auth()->id()) && $isRequired;
                            
                            // التحقق من حالة مشاهدة المحتوى
                            $contentProgress = \App\Models\StudentContentProgress::where('student_id', auth()->id())
                                ->where('content_id', $content->id)
                                ->first();
                            $isWatched = $contentProgress && $contentProgress->fully_watched;
                        @endphp
                        
                        <div class="list-group-item {{ $isLocked ? 'disabled' : '' }} 
                            {{ $showAsRequired && !$isWatched ? 'list-group-item-danger' : '' }}
                            {{ $isWatched ? 'list-group-item-success' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        {{ $content->title }}
                                        @if($showAsRequired && !$isWatched)
                                            <span class="badge bg-danger">مطلوب مشاهدته</span>
                                        @endif
                                        @if($isWatched)
                                            <span class="badge bg-success"><i class="fas fa-check"></i> تمت المشاهدة</span>
                                        @endif
                                    </h5>
                                    <p class="mb-1 text-muted">{{ $content->contentType->name ?? 'غير محدد' }} - {{ $content->duration ? $content->duration . ' دقيقة' : 'المدة غير محددة' }}</p>
                                </div>
                                <div>
                                    @if($content->is_required)
                                        <span class="badge bg-danger">إجباري</span>
                                    @else
                                        <span class="badge bg-secondary">اختياري</span>
                                    @endif
                                    
                                    @if($isLocked)
                                        <button class="btn btn-secondary btn-sm ms-2" disabled>
                                            <i class="fas fa-lock me-1"></i> مقفل
                                        </button>
                                    @elseif($showAsRequired && !$isWatched)
                                        <a href="{{ route('student.contents.show', [$course, $level, $content]) }}" class="btn btn-danger btn-sm ms-2">
                                            <i class="fas fa-play-circle me-1"></i> مشاهدة إجبارية
                                        </a>
                                    @elseif($isWatched)
                                        <a href="{{ route('student.contents.show', [$course, $level, $content]) }}" class="btn btn-success btn-sm ms-2">
                                            <i class="fas fa-eye me-1"></i> إعادة المشاهدة
                                        </a>
                                    @else
                                        <a href="{{ route('student.contents.show', [$course, $level, $content]) }}" class="btn btn-primary btn-sm ms-2">
                                            <i class="fas fa-play-circle me-1"></i> عرض
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    لا يوجد محتوى لهذا المستوى بعد.
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-4 text-center">
    <a href="{{ route('debug.failed-questions') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-bug me-1"></i> عرض تفاصيل الأسئلة التي أخطأت فيها
    </a>
</div>
@endsection 