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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">{{ $level->title }}</h2>
            @if(auth()->user()->can('update', $course))
            <div>
                <a href="{{ route('courses.levels.edit', [$course, $level]) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> تعديل المستوى
                </a>
                <a href="{{ route('courses.levels.contents.create', [$course, $level]) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> إضافة محتوى
                </a>
            </div>
            @endif
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
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        الحالة
                        <span class="badge {{ $level->status === 'active' ? 'bg-success' : 'bg-danger' }} rounded-pill">
                            {{ $level->status === 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </li>
                </ul>
            </div>

            @if($level->prerequisites && count($level->prerequisites) > 0)
                <div class="mt-3">
                    <h5>المتطلبات السابقة:</h5>
                    <ul class="list-group">
                        @foreach($level->prerequisiteLevels() as $prerequisite)
                            <li class="list-group-item">
                                {{ $prerequisite->title }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">محتويات المستوى</h3>
            @if(auth()->user()->can('update', $course))
            <a href="{{ route('courses.levels.contents.create', [$course, $level]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> إضافة محتوى
            </a>
            @endif
        </div>
        <div class="card-body">
            @if($level->contents->count() > 0)
                <div class="list-group">
                    @foreach($level->contents as $content)
                        <a href="{{ route('student.contents.show', [$course, $level, $content]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ $content->title }}</h5>
                                <p class="mb-1 text-muted">{{ $content->contentType->name ?? 'غير محدد' }} - {{ $content->duration ? $content->duration . ' دقيقة' : 'المدة غير محددة' }}</p>
                            </div>
                            <div>
                                @if($content->is_required)
                                    <span class="badge bg-danger">إجباري</span>
                                @else
                                    <span class="badge bg-secondary">اختياري</span>
                                @endif
                                <span class="ms-2"><i class="fas fa-chevron-right"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                @if($level->hasPreTest())
                    <div class="mt-4">
                        <h4>اختبار تحديد المستوى</h4>
                        @if($level->hasCompletedPreTest(auth()->id()))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> لقد أكملت اختبار تحديد المستوى بنجاح
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> يجب عليك إكمال اختبار تحديد المستوى قبل الانتقال للمستوى التالي
                                <div class="mt-2">
                                    <a href="{{ route('levels.pretest', [$course, $level]) }}" class="btn btn-primary">
                                        ابدأ اختبار تحديد المستوى
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    لا يوجد محتوى لهذا المستوى بعد.
                    @if(auth()->user()->can('update', $course))
                    <a href="{{ route('courses.levels.contents.create', [$course, $level]) }}">أضف محتوى جديد</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 