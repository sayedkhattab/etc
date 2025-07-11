<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - إثبات</title>
    <!-- Google Fonts - El Messiri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'El Messiri', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
            overflow-y: auto;
            z-index: 1030;
            transition: all 0.3s;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 0 15px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            color: #fff;
            margin-bottom: 0;
        }
        
        .sidebar-logo {
            max-width: 150px;
            height: auto;
        }
        
        .sidebar-menu {
            padding: 15px 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 10px 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu i {
            margin-left: 10px;
        }
        
        .sidebar-menu .nav-header {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 12px 15px 8px;
            margin: 20px 0 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
            border-top: none;
            background-color: #AB8A47;
        }
        
        .sidebar-menu .submenu {
            padding-right: 20px;
            display: none;
        }
        
        .sidebar-menu .submenu.show {
            display: block;
        }
        
        .sidebar-menu .has-submenu {
            position: relative;
        }
        
        .sidebar-menu .has-submenu > a::after {
            content: "\F282";
            font-family: "bootstrap-icons";
            position: absolute;
            left: 15px;
            transition: transform 0.3s;
        }
        
        .sidebar-menu .has-submenu > a.active::after {
            transform: rotate(90deg);
        }
        
        /* Content Styles */
        .content {
            margin-right: 250px;
            padding: 20px;
            transition: all 0.3s;
            width: calc(100% - 250px);
            position: absolute;
            left: 0;
            top: 0;
            min-height: 100vh;
            background-color: #f8f9fa;
            z-index: 1;
        }
        
        .navbar {
            background-color: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
            position: relative;
            z-index: 1020;
        }
        
        /* إخفاء أي عناصر غير مرغوب فيها */
        .navbar .dropdown-toggle::before,
        .navbar .dropdown-toggle::after {
            display: none !important;
        }
        
        /* تحسين مظهر اسم المستخدم */
        .navbar .dropdown-toggle {
            text-decoration: none;
            color: #343a40;
            font-weight: 500;
        }
        
        /* إصلاح مشكلة الرقم بجوار اسم الأدمن */
        .navbar .nav-link {
            position: relative;
        }
        
        .navbar .nav-link::before {
            display: none !important;
        }
        
        .navbar-brand {
            font-weight: 700;
        }
        
        .page-header {
            margin-bottom: 20px;
        }
        
        .page-header h1 {
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
                z-index: 1050;
            }
            
            .content {
                margin-right: 0;
                width: 100%;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .content.active {
                margin-right: 0;
                width: 100%;
            }
        }
        
        /* إصلاح مشكلة تداخل المحتوى مع القائمة الجانبية */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="إثبات" class="sidebar-logo">
            </div>
            
            <div class="sidebar-menu">
                <ul>
                    <!-- الرئيسية -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> الرئيسية
                        </a>
                    </li>
                    
                    <!-- إدارة المستخدمين -->
                    <li class="nav-header">إدارة المستخدمين</li>
                    <li class="has-submenu">
                        <a href="#userSubmenu" class="{{ request()->is('admin/admins*') || request()->is('admin/users*') || request()->is('admin/roles*') || request()->is('admin/court-users/judges*') || request()->is('admin/court-users/witnesses*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> المستخدمين
                        </a>
                        <ul class="submenu {{ request()->is('admin/admins*') || request()->is('admin/users*') || request()->is('admin/roles*') || request()->is('admin/court-users/judges*') || request()->is('admin/court-users/witnesses*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.admins.index') }}" class="{{ request()->routeIs('admin.admins.index') ? 'active' : '' }}">
                                    <i class="bi bi-person-gear"></i> المسؤولين
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    <i class="bi bi-person"></i> المستخدمين العاديين
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                                    <i class="bi bi-shield-check"></i> الأدوار والصلاحيات
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.court-users.judges.index') }}" class="{{ request()->routeIs('admin.court-users.judges.index') ? 'active' : '' }}">
                                    <i class="bi bi-person-badge"></i> إدارة القضاة
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.court-users.witnesses.index') }}" class="{{ request()->routeIs('admin.court-users.witnesses.index') ? 'active' : '' }}">
                                    <i class="bi bi-eye"></i> إدارة الشهود
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- إدارة المنصة التعليمية -->
                    <li class="nav-header">المنصة التعليمية</li>
                    <li class="has-submenu">
                        <a href="#courseSubmenu" class="{{ request()->is('admin/courses*') || request()->is('admin/categories*') ? 'active' : '' }}">
                            <i class="bi bi-mortarboard"></i> الدورات التعليمية
                        </a>
                        <ul class="submenu {{ request()->is('admin/courses*') || request()->is('admin/categories*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.courses.index') }}" class="{{ request()->routeIs('admin.courses.index') ? 'active' : '' }}">
                                    <i class="bi bi-collection"></i> جميع الدورات
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.courses.create') }}" class="{{ request()->routeIs('admin.courses.create') ? 'active' : '' }}">
                                    <i class="bi bi-plus-circle"></i> إضافة دورة جديدة
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                                    <i class="bi bi-tags"></i> تصنيفات الدورات
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="has-submenu">
                        <a href="#assessmentSubmenu" class="{{ request()->is('admin/assessments*') || request()->is('admin/questions*') ? 'active' : '' }}">
                            <i class="bi bi-clipboard-check"></i> التقييمات والاختبارات
                        </a>
                        <ul class="submenu {{ request()->is('admin/assessments*') || request()->is('admin/questions*') ? 'show' : '' }}">
                            <li>
                                <a href="#" class="{{ request()->routeIs('admin.assessments.index') ? 'active' : '' }}">
                                    <i class="bi bi-list-check"></i> جميع التقييمات
                                </a>
                            </li>
                            <li>
                                <a href="#" class="{{ request()->routeIs('admin.questions.index') ? 'active' : '' }}">
                                    <i class="bi bi-question-circle"></i> بنك الأسئلة
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="#" class="{{ request()->routeIs('admin.certificates.index') ? 'active' : '' }}">
                            <i class="bi bi-award"></i> الشهادات
                        </a>
                    </li>
                    
                    <!-- إدارة المحكمة الافتراضية -->
                    <li class="nav-header">المحكمة الافتراضية</li>
                    <li class="has-submenu">
                        <a href="#caseFilesSubmenu" class="{{ request()->is('admin/case-files*') ? 'active' : '' }}">
                            <i class="bi bi-folder-fill"></i> ملفات القضايا
                        </a>
                        <ul class="submenu {{ request()->is('admin/case-files*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.case-files.index') }}" class="{{ request()->routeIs('admin.case-files.index') ? 'active' : '' }}">
                                    <i class="bi bi-files"></i> عرض جميع الملفات
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.case-files.create') }}" class="{{ request()->routeIs('admin.case-files.create') ? 'active' : '' }}">
                                    <i class="bi bi-file-earmark-plus"></i> إضافة ملف قضية جديد
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.case-files.categories.index') }}" class="{{ request()->routeIs('admin.case-files.categories.index') ? 'active' : '' }}">
                                    <i class="bi bi-tags"></i> تصنيفات القضايا
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#activeCasesSubmenu" class="{{ request()->is('admin/active-cases*') ? 'active' : '' }}">
                            <i class="bi bi-briefcase"></i> القضايا النشطة
                        </a>
                        <ul class="submenu {{ request()->is('admin/active-cases*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.active-cases.index') }}" class="{{ request()->routeIs('admin.active-cases.index') ? 'active' : '' }}">
                                    <i class="bi bi-list-check"></i> جميع القضايا النشطة
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.active-cases.pending') }}" class="{{ request()->routeIs('admin.active-cases.pending') ? 'active' : '' }}">
                                    <i class="bi bi-hourglass-split"></i> قضايا في انتظار التعيين
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.active-cases.in-progress') }}" class="{{ request()->routeIs('admin.active-cases.in-progress') ? 'active' : '' }}">
                                    <i class="bi bi-play-circle"></i> قضايا قيد التنفيذ
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.active-cases.completed') }}" class="{{ request()->routeIs('admin.active-cases.completed') ? 'active' : '' }}">
                                    <i class="bi bi-check-circle"></i> قضايا مكتملة
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#courtUsersSubmenu" class="{{ request()->is('admin/court-users*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> مستخدمو المحكمة
                        </a>
                        <ul class="submenu {{ request()->is('admin/court-users*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.court-users.judges.index') }}" class="{{ request()->routeIs('admin.court-users.judges.index') ? 'active' : '' }}">
                                    <i class="bi bi-person-badge"></i> القضاة
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.court-users.students.index') }}" class="{{ request()->routeIs('admin.court-users.students.index') ? 'active' : '' }}">
                                    <i class="bi bi-mortarboard"></i> الطلاب
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.court-users.witnesses.index') }}" class="{{ request()->routeIs('admin.court-users.witnesses.index') ? 'active' : '' }}">
                                    <i class="bi bi-eye"></i> الشهود
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#courtSessionsSubmenu" class="{{ request()->is('admin/court-sessions*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event"></i> جلسات المحكمة
                        </a>
                        <ul class="submenu {{ request()->is('admin/court-sessions*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.court-sessions.index') }}" class="{{ request()->routeIs('admin.court-sessions.index') ? 'active' : '' }}">
                                    <i class="bi bi-calendar-week"></i> جميع الجلسات
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.court-sessions.upcoming') }}" class="{{ request()->routeIs('admin.court-sessions.upcoming') ? 'active' : '' }}">
                                    <i class="bi bi-calendar-plus"></i> الجلسات القادمة
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.court-sessions.completed') }}" class="{{ request()->routeIs('admin.court-sessions.completed') ? 'active' : '' }}">
                                    <i class="bi bi-calendar-check"></i> الجلسات المنتهية
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#judgmentsSubmenu" class="{{ request()->is('admin/judgments*') ? 'active' : '' }}">
                            <i class="bi bi-journal-text"></i> الأحكام القضائية
                        </a>
                        <ul class="submenu {{ request()->is('admin/judgments*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.judgments.index') }}" class="{{ request()->routeIs('admin.judgments.index') ? 'active' : '' }}">
                                    <i class="bi bi-journals"></i> جميع الأحكام
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.judgments.reconsiderations') }}" class="{{ request()->routeIs('admin.judgments.reconsiderations') ? 'active' : '' }}">
                                    <i class="bi bi-arrow-repeat"></i> طلبات إعادة النظر
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin.court-archives.index') }}" class="{{ request()->routeIs('admin.court-archives.index') ? 'active' : '' }}">
                            <i class="bi bi-archive"></i> أرشيف القضايا
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.court-reports.index') }}" class="{{ request()->routeIs('admin.court-reports.index') ? 'active' : '' }}">
                            <i class="bi bi-bar-chart"></i> تقارير وإحصائيات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.court-settings.index') }}" class="{{ request()->routeIs('admin.court-settings.index') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i> إعدادات المحكمة
                        </a>
                    </li>
                    
                    <!-- متجر القضايا -->
                    <li class="nav-header">متجر القضايا</li>
                    <li class="has-submenu">
                        <a href="#storeCategoriesSubmenu" class="{{ request()->is('admin/store/categories*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i> فئات القضايا
                        </a>
                        <ul class="submenu {{ request()->is('admin/store/categories*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.store.categories.index') }}" class="{{ request()->routeIs('admin.store.categories.index') ? 'active' : '' }}">
                                    <i class="bi bi-list"></i> عرض الفئات
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.store.categories.create') }}" class="{{ request()->routeIs('admin.store.categories.create') ? 'active' : '' }}">
                                    <i class="bi bi-plus-circle"></i> إضافة فئة جديدة
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#storeCaseFilesSubmenu" class="{{ request()->is('admin/store/case-files*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text"></i> ملفات القضايا
                        </a>
                        <ul class="submenu {{ request()->is('admin/store/case-files*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.store.case-files.index') }}" class="{{ request()->routeIs('admin.store.case-files.index') ? 'active' : '' }}">
                                    <i class="bi bi-files"></i> عرض جميع الملفات
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.store.case-files.create') }}" class="{{ request()->routeIs('admin.store.case-files.create') ? 'active' : '' }}">
                                    <i class="bi bi-file-earmark-plus"></i> إضافة ملف قضية جديد
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#storePurchasesSubmenu" class="{{ request()->is('admin/store/purchases*') ? 'active' : '' }}">
                            <i class="bi bi-cart"></i> المشتريات
                        </a>
                        <ul class="submenu {{ request()->is('admin/store/purchases*') ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('admin.store.purchases.index') }}" class="{{ request()->routeIs('admin.store.purchases.index') ? 'active' : '' }}">
                                    <i class="bi bi-list-check"></i> جميع المشتريات
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.store.purchases.create') }}" class="{{ request()->routeIs('admin.store.purchases.create') ? 'active' : '' }}">
                                    <i class="bi bi-plus-circle"></i> إضافة عملية شراء جديدة
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- الإعدادات -->
                    <li class="nav-header">الإعدادات</li>
                    <li>
                        <a href="#" class="{{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i> إعدادات النظام
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                            <i class="bi bi-person-circle"></i> الملف الشخصي
                        </a>
                    </li>
                    
                    <!-- تسجيل الخروج -->
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-left"></i> تسجيل الخروج
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary d-md-none me-3" id="sidebar-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <a class="navbar-brand d-md-none" href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="إثبات" height="30">
                    </a>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::guard('admin')->user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::guard('admin')->user()->avatar) }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;" alt="صورة المستخدم">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person text-white" style="font-size: 16px;"></i>
                                    </div>
                                @endif
                                <span>{{ Auth::guard('admin')->user()->name ?? 'مدير النظام' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="#">الإعدادات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        تسجيل الخروج
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <div class="container-fluid">
                @yield('content')
            </div>
            
            <div style="clear: both;"></div>
        </div>
    </div>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Sidebar
            document.getElementById('sidebar-toggle').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
                document.querySelector('.content').classList.toggle('active');
            });
            
            // Toggle Submenu
            const submenuToggles = document.querySelectorAll('.has-submenu > a');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const submenu = parent.querySelector('.submenu');
                    
                    // Toggle active class
                    this.classList.toggle('active');
                    
                    // Toggle submenu
                    if (submenu.classList.contains('show')) {
                        submenu.classList.remove('show');
                    } else {
                        submenu.classList.add('show');
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>
@stack('scripts')
</html> 