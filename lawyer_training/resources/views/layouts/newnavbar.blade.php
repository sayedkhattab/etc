<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>منصة بيان للتدريب | الرئيسية</title>
    <link rel="icon" href="../assets/frontend/img/favicon.png" sizes="20x20" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/flaticon.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/nice-select.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/magnific.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/spacing.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('../assets/frontend/css/style.css') }}" />

    <style>
        body {
            padding-top: 170px;
        }

        .navbar {
            position: fixed;
            width: 100%;
            z-index: 1000;
            background-color: white;
        }
    </style>
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
    <nav class="navbar navbar--two navbar-area navbar-expand-lg navbar-bg">
        <div class="container nav-container">
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
