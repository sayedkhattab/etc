@php
    // تحقق مما إذا كان المستخدم قد أكمل محتوى إجباري مؤخرًا
    $hasCompletedRequiredContent = false;
    $completedRequiredContentMessage = '';
    
    if (auth()->check() && session('required_content_completed')) {
        $hasCompletedRequiredContent = true;
        $completedRequiredContentMessage = session('required_content_completed');
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        /* أساسيات */
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* أنماط الشريط الجانبي */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }
        
        .sidebar-header {
            position: relative;
            padding: 5px 0 !important;
            margin-top: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            margin-bottom: 15px;
        }
        
        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(to right, #4b6cb7, #182848);
        }
        
        .sidebar-header .small {
            opacity: 0.8;
            font-size: 0.8rem;
        }
        
        .sidebar .position-sticky {
            padding-top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.75rem 1.25rem;
            border-radius: 0 5px 5px 0;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateX(-3px);
        }
        
        .sidebar .nav-link.active {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            border-right: 3px solid #0d6efd;
        }
        
        .sidebar .nav-link i {
            margin-left: 0.5rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            padding: 0.75rem 1.25rem;
            margin-top: 15px;
            color: #6c757d;
            font-weight: 600;
        }
        
        /* أنماط لوحة التحكم */
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            font-weight: 600;
        }
        
        .dashboard-card .card-body {
            padding: 1.25rem;
        }
        
        .dashboard-stat {
            text-align: center;
            padding: 20px;
        }
        
        .dashboard-stat .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #0d6efd;
        }
        
        .dashboard-stat .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .dashboard-stat .stat-label {
            color: #6c757d;
        }
        
        /* إضافة أنماط للإشعارات */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .notification-toast.hide {
            animation: slideOut 0.5s ease-in forwards;
        }
        
        .notification-toast .toast-header {
            background-color: #28a745;
            color: white;
            border-bottom: none;
            padding: 0.75rem 1rem;
        }
        
        .notification-toast .toast-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 1;
        }
        
        .notification-toast .toast-body {
            padding: 15px;
            background-color: white;
        }
        
        .notification-toast .achievement-icon {
            font-size: 24px;
            color: #ffc107;
            margin-right: 10px;
            background-color: rgba(255, 193, 7, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-toast .progress {
            height: 5px;
            margin-top: 10px;
        }
        
        .notification-toast .progress-bar {
            background-color: #28a745;
            animation: progressAnimation 5s linear forwards;
            width: 100%;
        }
        
        @keyframes progressAnimation {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
        
        /* أنماط للشريط العلوي */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            background-color: #fff;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        /* تصحيح للعرض في الاتجاه من اليمين إلى اليسار */
        .ms-sm-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }
        
        .me-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }
        
        .me-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }
        
        /* تنسيق المحتوى الرئيسي */
        main {
            padding-top: 20px;
            min-height: calc(100vh - 56px);
        }
        
        /* تنسيق أزرار التنقل */
        .breadcrumb {
            background-color: #fff;
            border-radius: 8px;
            padding: 12px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            margin-bottom: 25px;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            float: right;
            padding-left: 0.5rem;
            padding-right: 0;
        }
        
        /* تنسيق البطاقات */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,.1);
        }
        
        /* تنسيق الأزرار */
        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,.1);
        }
        
        /* تنسيق الجداول */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* تنسيق للشاشات الصغيرة */
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                height: auto;
                padding-top: 0;
            }
            
            main {
                padding-top: 10px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('partials.navbar')

        <div class="container-fluid">
            <div class="row">
                @auth
                    @include('partials.sidebar')
                    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                        @yield('content')
                    </main>
                @else
                    <main class="col-12">
                        @yield('content')
                    </main>
                @endauth
            </div>
        </div>
    </div>

    <!-- إشعار إكمال المحتوى الإجباري -->
    @if($hasCompletedRequiredContent)
        <div class="notification-toast" id="requiredContentNotification">
            <div class="toast-header">
                <strong class="me-auto"><i class="fas fa-trophy me-2"></i> إنجاز جديد</strong>
                <button type="button" class="btn-close" onclick="closeNotification()"></button>
            </div>
            <div class="toast-body">
                <div class="d-flex align-items-center">
                    <span class="achievement-icon"><i class="fas fa-star"></i></span>
                    <div class="ms-2">
                        <h5 class="mb-1 fw-bold">تهانينا!</h5>
                        <p class="mb-0">{{ $completedRequiredContentMessage }}</p>
                    </div>
                </div>
                <div class="progress mt-3">
                    <div class="progress-bar" role="progressbar"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function closeNotification() {
            const notification = document.getElementById('requiredContentNotification');
            notification.classList.add('hide');
            setTimeout(() => {
                notification.style.display = 'none';
            }, 500);
        }

        // إغلاق الإشعار تلقائيًا بعد 5 ثوانٍ
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('requiredContentNotification');
            if (notification) {
                setTimeout(() => {
                    closeNotification();
                }, 5000);
            }
        });
    </script>
    @stack('scripts')
</body>
</html> 