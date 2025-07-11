@extends('layouts.app')

@section('title', 'لوحة الدورة: ' . ($course->title ?? 'دورة'))

@push('styles')
<style>
    .course-header {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .course-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .level-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
        border: none;
    }
    
    .level-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .level-card .card-header {
        border-bottom: none;
        font-weight: 600;
    }
    
    .content-list .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0;
        padding: 12px 15px;
        transition: background-color 0.2s ease;
    }
    
    .content-list .list-group-item:first-child {
        border-top: none;
    }
    
    .content-list .list-group-item:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .progress {
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .required-content-card {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 1px rgba(220, 53, 69, 0.25);
    }
    
    .required-content-card .card-header {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        font-weight: 600;
    }
    
    .completed-level-card {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 1px rgba(40, 167, 69, 0.25);
    }
    
    .completed-level-card .card-header {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .course-progress-container {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .course-progress-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .course-progress-bar {
        height: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .course-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .breadcrumb-container {
        background-color: #fff;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .breadcrumb {
        margin-bottom: 0;
        padding: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="breadcrumb-container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.catalog') }}">الدورات</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
            </ol>
        </nav>
    </div>

    <div class="course-header">
        <h2 class="course-title">{{ $course->title }}</h2>
        
        @if($course->description)
            <p class="text-muted">{{ Str::limit($course->description, 200) }}</p>
        @endif
    </div>

    @php
        // حساب التقدم الإجمالي في الدورة
        $totalLevels = $levels->count();
        $completedLevels = 0;
        
        foreach ($levels as $level) {
            $isLevelCompleted = $level->contents->every(function($content) use ($progress) {
                $prg = $progress->get($content->id);
                return $prg && $prg->fully_watched;
            });
            
            if ($isLevelCompleted) {
                $completedLevels++;
            }
        }
        
        $courseProgress = $totalLevels > 0 ? ($completedLevels / $totalLevels) * 100 : 0;
        
        // الحصول على المحتويات الإجبارية للطالب
        $requiredContents = [];
        $failedQuestions = \App\Models\FailedQuestion::where('student_id', auth()->id())
            ->where('resolved', false)
            ->with(['question.content', 'level'])
            ->get();
            
        foreach ($failedQuestions as $failedQuestion) {
            if ($failedQuestion->question && $failedQuestion->question->content) {
                // التحقق مما إذا كان المحتوى قد تمت مشاهدته بالفعل
                $contentProgress = $progress->get($failedQuestion->question->content->id);
                $isCompleted = $contentProgress && $contentProgress->fully_watched;
                
                // إذا لم يكن مكتملاً، أضفه إلى قائمة المحتويات المطلوبة
                if (!$isCompleted) {
                    $requiredContents[] = [
                        'content' => $failedQuestion->question->content,
                        'level' => $failedQuestion->level,
                        'question' => $failedQuestion->question
                    ];
                }
            }
        }
    @endphp

    <div class="course-progress-container">
        <h5 class="course-progress-title">تقدم الدورة</h5>
        <div class="progress course-progress-bar">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $courseProgress }}%;" aria-valuenow="{{ $courseProgress }}" aria-valuemin="0" aria-valuemax="100">{{ round($courseProgress) }}%</div>
        </div>
        <div class="course-stats">
            <span>{{ $completedLevels }} من {{ $totalLevels }} مستويات مكتملة</span>
            @if($courseProgress >= 100)
                <span class="badge bg-success">أكملت الدورة</span>
            @endif
        </div>
    </div>

    @if(!empty($requiredContents))
        <div class="card mb-4 required-content-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> محتويات إجبارية مطلوبة</h5>
            </div>
            <div class="card-body">
                <p>يجب عليك مشاهدة المحتويات التالية لتحسين فهمك للمواضيع التي رسبت فيها في اختبار تحديد المستوى:</p>
                <div class="list-group content-list">
                    @foreach($requiredContents as $item)
                        @php
                            $contentProgress = $progress->get($item['content']->id);
                            $isCompleted = $contentProgress && $contentProgress->fully_watched;
                        @endphp
                        <a href="{{ route('student.contents.show', [$course->id, $item['level']->id, $item['content']->id]) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $isCompleted ? 'list-group-item-success' : 'list-group-item-danger' }}">
                            <div>
                                <i class="fas fa-play-circle me-2"></i>
                                <strong>{{ $item['content']->title }}</strong>
                                <span class="text-muted"> - المستوى: {{ $item['level']->title }}</span>
                            </div>
                            @if($isCompleted)
                                <span class="badge bg-success rounded-pill"><i class="fas fa-check-circle"></i> تم الإكمال</span>
                            @else
                                <span class="badge bg-danger rounded-pill"><i class="fas fa-exclamation-circle"></i> مطلوب</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        @forelse($levels as $level)
            @php
                $contentsCompleted = $level->contents->every(function($content) use ($progress) {
                    $prg = $progress->get($content->id);
                    return $prg && $prg->fully_watched;
                });
                
                $cardClass = $contentsCompleted ? 'completed-level-card' : '';
                
                // التحقق من وجود محتوى إجباري في هذا المستوى
                $hasRequiredContent = false;
                foreach ($level->contents as $content) {
                    $contentProgress = $progress->get($content->id);
                    if ($contentProgress && $contentProgress->is_required_content && !$contentProgress->fully_watched) {
                        $hasRequiredContent = true;
                        break;
                    }
                }
                
                if ($hasRequiredContent) {
                    $cardClass = 'required-content-card';
                }
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 level-card {{ $cardClass }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $loop->iteration }}. {{ $level->title }}</h5>
                        @if($contentsCompleted)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i> تم الإكمال
                            </span>
                        @elseif($hasRequiredContent)
                            <span class="badge bg-danger">
                                <i class="fas fa-exclamation-circle me-1"></i> محتوى إجباري مطلوب
                            </span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        {{-- حالة المستوى --}}
                        @php
                            $hasFailed = $failedQuestions->has($level->id);
                            // هل بدأ الطالب أي محتوى في هذا المستوى؟
                            $hasStartedContent = $level->contents->pluck('id')->some(function($cid) use ($progress) {
                                return $progress->has($cid);
                            });
                            $testPassed = !$hasFailed && $hasStartedContent;
                            
                            // هل أخذ الطالب اختبار تحديد المستوى من قبل؟
                            $hasTakenTest = isset($testedLevels) && $testedLevels->contains($level->id);
                                
                            // هل شاهد الطالب أي من محتويات هذا المستوى؟
                            $hasWatchedContent = $level->contents->pluck('id')->some(function($cid) use ($progress) {
                                $prg = $progress->get($cid);
                                return $prg && $prg->watched_seconds > 0;
                            });
                        @endphp

                        <ul class="list-unstyled mb-3">
                            <li class="mb-2">
                                <i class="fas fa-check-circle {{ $testPassed ? 'text-success' : 'text-muted' }} me-2"></i>
                                اختبار تحديد المستوى: 
                                @if($testPassed)
                                    <span class="text-success">مجتاز</span>
                                @elseif($hasTakenTest)
                                    <span class="text-danger">غير مجتاز</span>
                                @else
                                    <span class="text-muted">لم يتم البدء</span>
                                @endif
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-book-reader {{ $hasWatchedContent ? 'text-success' : 'text-muted' }} me-2"></i>
                                المحتوى: {{ $level->contents->count() }} وحدة
                            </li>
                        </ul>

                        <div class="mt-auto">
                            <a href="{{ route('student.levels.show', [$course, $level]) }}" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-1"></i> عرض المستوى
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    لا توجد مستويات متاحة في هذه الدورة بعد.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection 