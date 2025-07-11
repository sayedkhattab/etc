<!-- Sidebar -->
<div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky">
        <!-- شعار المنصة في هيدر بخلفية غامقة -->
        <div class="sidebar-header bg-dark text-white py-3">
            <div class="text-center">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="إثبات" class="img-fluid" style="max-width: 140px; max-height: 60px;">
                @else
                    <h3 class="fs-4 fw-bold text-white mb-0">إثبات</h3>
                @endif
                <div class="small mt-2"></div>
            </div>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i>
                    لوحة التحكم
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('courses*') && !request()->is('*/courses/*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                    <i class="fas fa-book me-2"></i>
                    الدورات
                </a>
            </li>
            
            @if(auth()->user()->hasRole('student'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('course-catalog*') || request()->is('student/courses*') ? 'active' : '' }}" href="{{ route('courses.catalog') }}">
                        <i class="fas fa-graduation-cap me-2"></i>
                        دوراتي
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('student/certificates*') ? 'active' : '' }}" href="{{ route('student.certificates') }}">
                        <i class="fas fa-certificate me-2"></i>
                        شهاداتي
                    </a>
                </li>
            @endif
            
            @if(auth()->user()->hasRole('admin'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        لوحة الإدارة
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users me-2"></i>
                        المستخدمين
                    </a>
                </li>
            @endif
            
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                    <i class="fas fa-user me-2"></i>
                    الملف الشخصي
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
        
        <!-- قسم المحتوى الإجباري إذا كان هناك محتوى إجباري للطالب -->
        @if(auth()->user()->hasRole('student') && isset($requiredContents) && count($requiredContents) > 0)
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>المحتوى الإجباري</span>
            </h6>
            <ul class="nav flex-column mb-2">
                @foreach($requiredContents as $content)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.contents.show', [$content->level->course_id, $content->level_id, $content->id]) }}">
                            <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                            {{ $content->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div> 