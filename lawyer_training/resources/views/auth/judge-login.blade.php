<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>منصة بيان للتدريب | تسجيل دخول القضاة</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="icon" href="../assets/frontend/img/favicon.png" sizes="20x20" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/flaticon.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/nice-select.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/magnific.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/spacing.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/style.css') }}" />
</head>

<body class='sc5'>

    <!-- preloader area start -->
    <div class="preloader" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <!-- search popup start-->
    <div class="td-search-popup" id="td-search-popup">
        <form action="#" class="search-form">
            <div class="form-group">
                <input type="text" class="form-control" style="direction: rtl;" placeholder="البحث عن...">
            </div>
            <button type="submit" class="submit-btn"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <!-- search popup end-->
    <div class="body-overlay" id="body-overlay"></div>

    <!-- navbar start -->
    <nav class="navbar navbar--two navbar-area navbar-expand-lg">
        <div class="container nav-container navbar-bg">
            <div class="responsive-mobile-menu">
                <button class="menu toggle-btn d-block d-lg-none" data-target="#Iitechie_main_menu"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-left"></span>
                    <span class="icon-right"></span>
                </button>
            </div>
            <div class="logo">
                <a href="#"><img src="../assets/frontend/img/logos/logo.png" alt="img"></a>
            </div>
            <div class="nav-right-part nav-right-part-mobile">
                <a class="search-bar-btn" href="#">
                    <i class="flaticon-magnifying-glass"></i>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="Iitechie_main_menu">
                <ul class="navbar-nav menu-open text-lg-end">
                    <li class="menu-item-has-children">
                        <a href="#">الرئيسية</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">من نحن</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">كيف نعمل؟</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">الدورات التدريبية</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">الأخبار</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">تواصل معنا</a>
                    </li>
                </ul>
            </div>
            <div class="nav-right-part nav-right-part-desktop">
                <a class="search-bar-btn" href="#">
                    <i class="flaticon-magnifying-glass"></i>
                </a>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="flaticon-user-1"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" style="text-align: right;" href="{{ url('/login') }}">دخول الطلاب</a>
                        <a class="dropdown-item" style="text-align: right;" href="{{ url('/judge/login') }}">دخول القضاة</a>
                    </div>
                </div>
                <a class="btn btn--style-two" style="font-weight: 400 !important;" href="{{ route('register.student') }}">متدرب جديد</a>
            </div>
        </div>
    </nav>
    <!-- navbar end -->

    <!-- Hero start -->
    <div class="hero-area-two bgs-cover overlay py-250" style="position: relative; overflow: hidden; direction: rtl;">
        <video autoplay muted loop playsinline class="background-video">
            <source src="../assets/frontend/img/hero/background.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="overlay-layer"></div>
        <div class="container" style="position: relative; z-index: 1;">
            <div class="hero-content mt-110 rmt-0 mb-65 text-center text-white rel z-1">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('judge.login.submit') }}" enctype="multipart/form-data" style="margin-top: 25px !important; margin-bottom: 25px !important; background-color: rgba(var(--bs-light-rgb), var(--bs-bg-opacity)) !important; padding: 30px; border-radius: 15px; max-width: 80%; margin: auto;">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('البريد الالكتروني') }}</label>
                        <input id="email" type="email" class="form-control" style="direction: ltr;" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        @error('email')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('كلمة المرور') }}</label>
                        <input id="password" type="password" class="form-control" style="direction: ltr;" name="password" required autocomplete="current-password" />
                        @error('password')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn btn-primary" type="submit" style="font-family: Alexandria;">
                            {{ __('دخول') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Hero end -->

    <script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/skill.bars.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/magnific.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/appear.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/isotope.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/imageload.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/main.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
