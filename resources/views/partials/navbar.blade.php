<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="إثبات" height="40">
            @else
                <span class="fs-4 fw-bold text-primary">إثبات</span>
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#courses">الدورات التدريبية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">من نحن</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#how">كيف نعمل</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cases">متجر القضايا</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">تواصل معنا</a>
                </li>
            </ul>
            <div class="d-flex">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">لوحة الطالب</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                                    تسجيل الخروج
                                </a>
                                <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">تسجيل الدخول</a>
                @endauth
            </div>
        </div>
    </div>
</nav> 