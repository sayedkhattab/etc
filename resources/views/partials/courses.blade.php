<section class="courses" id="courses">
    <div class="container">
        <div class="section-title">
            <h2>أحدث الدورات التعليمية</h2>
        </div>
        
        <div class="row g-4">
            @if(isset($courses) && $courses->count())
                @foreach($courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="card course-card h-100">
                            @if($course->is_featured)
                                <span class="badge bg-primary course-badge">مميز</span>
                            @endif
                            <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://via.placeholder.com/600x400' }}" class="card-img-top course-img" alt="{{ $course->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->title }}</h5>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-user-tie text-muted me-2"></i>
                                    <small class="text-muted">{{ $course->instructor->name ?? 'غير محدد' }}</small>
                                </div>
                                <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="{{ $course->price > 0 ? 'text-primary' : 'text-success' }} fw-bold">
                                        {{ $course->price > 0 ? $course->price . ' ريال' : 'مجاناً' }}
                                    </span>
                                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-outline-primary">التفاصيل</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Static fallback for empty state --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card course-card h-100">
                        <span class="badge bg-primary course-badge">جديد</span>
                        <img src="https://images.unsplash.com/photo-1589391886645-d51941baf7fb?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="card-img-top course-img" alt="القانون الجنائي">
                        <div class="card-body">
                            <h5 class="card-title">مبادئ القانون الجنائي</h5>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-tie text-muted me-2"></i>
                                <small class="text-muted">د. أحمد محمود</small>
                            </div>
                            <p class="card-text">تعرف على المبادئ الأساسية للقانون الجنائي وتطبيقاته في النظام القضائي.</p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">مجاناً</span>
                                <a href="#" class="btn btn-outline-primary">التفاصيل</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card course-card h-100">
                        <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="card-img-top course-img" alt="القانون المدني">
                        <div class="card-body">
                            <h5 class="card-title">أساسيات القانون المدني</h5>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-tie text-muted me-2"></i>
                                <small class="text-muted">د. سارة الخالدي</small>
                            </div>
                            <p class="card-text">دورة شاملة في القانون المدني تغطي العقود والالتزامات والمسؤولية التقصيرية.</p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">299 ريال</span>
                                <a href="#" class="btn btn-outline-primary">التفاصيل</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card course-card h-100">
                        <span class="badge bg-danger course-badge">الأكثر طلباً</span>
                        <img src="https://images.unsplash.com/photo-1589578527966-fdac0f44566c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="card-img-top course-img" alt="المرافعات">
                        <div class="card-body">
                            <h5 class="card-title">فن المرافعة القضائية</h5>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-tie text-muted me-2"></i>
                                <small class="text-muted">المستشار محمد العتيبي</small>
                            </div>
                            <p class="card-text">تعلم أصول المرافعة القضائية وأساليب الإقناع وبناء الحجة القانونية السليمة.</p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">499 ريال</span>
                                <a href="#" class="btn btn-outline-primary">التفاصيل</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('courses.catalog') }}" class="btn btn-primary btn-lg">عرض جميع الدورات</a>
        </div>
    </div>
</section> 