@extends('layouts.app')

@section('title', 'كتالوج الدورات - إثبات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>كتالوج الدورات</h2>
        <div>
            <form action="{{ route('courses.catalog') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="ابحث عن دورة..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>
    </div>
    
    <div class="row">
        <!-- فلاتر البحث -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">الفلاتر</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('courses.catalog') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label fw-bold">التصنيفات</label>
                            @foreach($categories ?? [] as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}" 
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">المستوى</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="levels[]" value="مبتدئ" id="level-beginner" 
                                    {{ in_array('مبتدئ', request('levels', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-beginner">
                                    مبتدئ
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="levels[]" value="متوسط" id="level-intermediate" 
                                    {{ in_array('متوسط', request('levels', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-intermediate">
                                    متوسط
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="levels[]" value="متقدم" id="level-advanced" 
                                    {{ in_array('متقدم', request('levels', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="level-advanced">
                                    متقدم
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">السعر</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="price_types[]" value="free" id="price-free" 
                                    {{ in_array('free', request('price_types', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="price-free">
                                    مجاني
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="price_types[]" value="paid" id="price-paid" 
                                    {{ in_array('paid', request('price_types', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="price-paid">
                                    مدفوع
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">تطبيق الفلاتر</button>
                        <a href="{{ route('courses.catalog') }}" class="btn btn-outline-secondary w-100 mt-2">إعادة تعيين</a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- قائمة الدورات -->
        <div class="col-md-9">
            <div class="row g-4">
                @forelse($courses ?? [] as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            @if($course->is_featured)
                                <span class="badge bg-primary position-absolute top-0 end-0 m-2">مميز</span>
                            @endif
                            
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->title }}</h5>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-tie text-muted me-2"></i>
                                    <small class="text-muted">{{ $course->instructor->name ?? 'غير محدد' }}</small>
                                </div>
                                
                                <div class="mb-3">
                                    @if($course->category)
                                        <span class="badge bg-secondary">{{ $course->category->name }}</span>
                                    @endif
                                    <span class="badge bg-info">{{ $course->difficulty_level }}</span>
                                </div>
                                
                                <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-2">
                                        <i class="fas fa-users text-muted"></i>
                                        <small class="text-muted">{{ $course->enrolled_count ?? 0 }} طالب</small>
                                    </div>
                                    <div class="me-2">
                                        <i class="fas fa-clock text-muted"></i>
                                        <small class="text-muted">{{ $course->duration ?? 0 }} ساعة</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-white border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold {{ $course->price > 0 ? 'text-primary' : 'text-success' }}">
                                        @if($course->price > 0)
                                            {{ $course->price }} ريال
                                        @else
                                            مجاناً
                                        @endif
                                    </span>
                                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-outline-primary">عرض التفاصيل</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <p class="mb-0">لا توجد دورات متاحة حالياً تطابق معايير البحث.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $courses->links() ?? '' }}
            </div>
        </div>
    </div>
</div>
@endsection 