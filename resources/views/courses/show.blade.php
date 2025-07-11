@extends('layouts.app')

@section('title', $course->title ?? 'تفاصيل الدورة' . ' - إثبات')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses.catalog') }}">الدورات</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $course->title ?? 'تفاصيل الدورة' }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- تفاصيل الدورة -->
        <div class="col-lg-8">
            <div class="card mb-4">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="card-title mb-0">{{ $course->title }}</h2>
                        @if($course->is_featured)
                            <span class="badge bg-primary">مميز</span>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        @if($course->category)
                            <span class="badge bg-secondary me-2">{{ $course->category->name }}</span>
                        @endif
                        <span class="badge bg-info me-2">{{ $course->difficulty_level }}</span>
                        <span class="badge bg-{{ $course->is_active ? 'success' : 'danger' }}">
                            {{ $course->is_active ? 'متاح' : 'غير متاح' }}
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-4">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span>{{ $course->enrolled_count ?? 0 }} طالب</span>
                        </div>
                        <div class="me-4">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span>{{ $course->duration ?? 0 }} ساعة</span>
                        </div>
                        <div>
                            <i class="fas fa-layer-group text-muted me-2"></i>
                            <span>{{ $course->levels_count ?? 0 }} مستوى</span>
                        </div>
                    </div>
                    
                    <h5 class="mb-3">وصف الدورة</h5>
                    <div class="mb-4">
                        {!! nl2br(e($course->description)) !!}
                    </div>
                    
                    <h5 class="mb-3">ما ستتعلمه</h5>
                    <div class="mb-4">
                        @if($course->learning_outcomes)
                            <ul>
                                @foreach(explode("\n", $course->learning_outcomes) as $outcome)
                                    @if(trim($outcome))
                                        <li>{{ trim($outcome) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">لا توجد مخرجات تعليمية محددة.</p>
                        @endif
                    </div>
                    
                    <h5 class="mb-3">المتطلبات السابقة</h5>
                    <div class="mb-4">
                        @if($course->prerequisites)
                            <ul>
                                @foreach(explode("\n", $course->prerequisites) as $prerequisite)
                                    @if(trim($prerequisite))
                                        <li>{{ trim($prerequisite) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">لا توجد متطلبات سابقة.</p>
                        @endif
                    </div>
                    
                    <h5 class="mb-3">المستويات</h5>
                    <div class="accordion mb-4" id="accordionLevels">
                        @forelse($course->levels ?? [] as $level)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $level->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $level->id }}" aria-expanded="false" aria-controls="collapse-{{ $level->id }}">
                                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                            <span>{{ $level->title }}</span>
                                            <small class="text-muted">{{ $level->contents_count ?? 0 }} محتوى</small>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $level->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $level->id }}" data-bs-parent="#accordionLevels">
                                    <div class="accordion-body">
                                        <p>{{ $level->description }}</p>
                                        
                                        @if($level->contents && $level->contents->count() > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach($level->contents as $content)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            @if($content->type)
                                                                <i class="fas {{ $content->type->icon ?? 'fa-file' }} text-muted me-2"></i>
                                                            @endif
                                                            {{ $content->title }}
                                                        </div>
                                                        <span class="text-muted">{{ $content->duration ?? 0 }} دقيقة</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted">لا يوجد محتوى في هذا المستوى.</p>
                                        @endif
                                        
                                        @if($level->assessments && $level->assessments->count() > 0)
                                            <h6 class="mt-3 mb-2">التقييمات</h6>
                                            <ul class="list-group list-group-flush">
                                                @foreach($level->assessments as $assessment)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-tasks text-muted me-2"></i>
                                                            {{ $assessment->title }}
                                                        </div>
                                                        <span class="badge bg-info">{{ $assessment->type->name ?? 'تقييم' }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                لا توجد مستويات في هذه الدورة.
                            </div>
                        @endforelse
                    </div>
                    
                    {{-- تم حذف قسم المدرس بناءً على طلب العميل --}}
                </div>
            </div>
        </div>
        
        <!-- بطاقة التسجيل -->
        <div class="col-lg-4">
            <div class="card mb-4 position-sticky" style="top: 80px;">
                <div class="card-body">
                    <h4 class="card-title mb-4">التسجيل في الدورة</h4>
                    
                    <div class="mb-4">
                        <h3 class="mb-0 {{ $course->price > 0 ? 'text-primary' : 'text-success' }}">
                            @if($course->price > 0)
                                {{ $course->price }} ريال
                            @else
                                مجاناً
                            @endif
                        </h3>
                    </div>
                    
                    @if($isEnrolled ?? false)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            أنت مسجل بالفعل في هذه الدورة
                        </div>
                        <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-primary w-100 mb-2">الدخول إلى الدورة</a>
                    @else
                        @if($course->is_active)
                            <form action="{{ $course->price > 0 ? route('checkout', $course->id) : route('courses.enroll', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 mb-2">التسجيل في الدورة</button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100 mb-2" disabled>الدورة غير متاحة حالياً</button>
                        @endif
                    @endif
                    
                    <div class="mt-4">
                        <h6>تتضمن هذه الدورة:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-video text-muted me-2"></i> وصول كامل للمحتوى</li>
                            <li class="mb-2"><i class="fas fa-file-pdf text-muted me-2"></i> مواد تعليمية للتحميل</li>
                            <li class="mb-2"><i class="fas fa-tasks text-muted me-2"></i> تقييمات وتمارين</li>
                            <li class="mb-2"><i class="fas fa-certificate text-muted me-2"></i> شهادة إتمام</li>
                            <li class="mb-2"><i class="fas fa-infinity text-muted me-2"></i> وصول مدى الحياة</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fas fa-share-alt me-2"></i> مشاركة الدورة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 