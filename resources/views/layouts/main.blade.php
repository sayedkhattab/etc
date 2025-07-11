<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'إثبات - منصة التعليم القانوني والمحكمة الافتراضية' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Inline Styles (mأخوذة من التصميم الحالي) -->
    <style>
        :root {
            --gold-dark: #AB8A47;
            --gold-light: #BC9F59;
            --beige-bg: #F8F7F4;
        }

        body {
            font-family: 'El Messiri', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        /* ===== Navbar ===== */
        .navbar {
            background: transparent !important;
            padding: 15px 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .navbar-brand img { width: 200px; height: auto; }
        .navbar-nav .nav-link { color: #ffffff; font-weight: 500; margin-left: 1rem; }
        .navbar-nav .nav-link:hover,.navbar-nav .nav-link.active { color: var(--gold-light); }

        /* ===== Buttons ===== */
        .btn-gold { background-color: var(--gold-dark); color: #fff; border: 2px solid var(--gold-dark); padding: 12px 40px; border-radius: 10px; }
        .btn-gold:hover { background-color: var(--gold-light); border-color: var(--gold-light); color: #fff; }
        .btn-outline-gold { background-color: transparent; color: var(--gold-dark); border: 2px solid var(--gold-dark); padding: 12px 40px; border-radius: 10px; }
        .btn-outline-gold:hover { background-color: var(--gold-dark); color: #fff; }

        /* ===== Hero ===== */
        .hero { background: url('/images/hero.png') no-repeat center center / cover; color: #fff; padding: 120px 0 0; position: relative; overflow: hidden; min-height: 100vh !important; width: 100%; display: flex; align-items: center; justify-content: center; text-align: center; }
        .hero::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.55); }
        .hero > .container { position: relative; z-index: 2; }
        .hero h1 { font-weight: 700; font-size: 3.5rem; margin-bottom: 20px; color: #fff; }
        .hero p { font-size: 1.25rem; margin-bottom: 40px; color: #fff; opacity: 0.9; }

        /* ===== Features ===== */
        .features { padding: 80px 0; }
        .feature-box { padding: 30px; text-align: center; border-radius: 10px; transition: all 0.3s ease; height: 100%; }
        .feature-box:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        .feature-icon { width: 80px; height: 80px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 2rem; color: white; }
        .feature-icon.education { background-color: #007bff; }
        .feature-icon.court { background-color: #28a745; }
        .feature-icon.certificate { background-color: #fd7e14; }
        .feature-icon.community { background-color: #6f42c1; }
        .feature-title { font-weight: 700; margin-bottom: 15px; }

        /* ===== Courses ===== */
        .courses { background-color: #f8f9fa; padding: 80px 0; }
        .section-title { text-align: center; margin-bottom: 50px; }
        .section-title h2 { font-weight: 700; position: relative; display: inline-block; padding-bottom: 15px; }
        .section-title h2::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background-color: #007bff; }
        .course-card { border: none; border-radius: 10px; overflow: hidden; transition: all 0.3s ease; height: 100%; }
        .course-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        .course-img { height: 200px; object-fit: cover; }
        .course-badge { position: absolute; top: 15px; right: 15px; }

        /* ===== Testimonials & CTA ===== */
        .testimonials { padding: 80px 0; }
        .testimonial-card { padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .testimonial-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 20px; }
        .cta { background: linear-gradient(135deg, #6f42c1 0%, #4e2a84 100%); color: white; padding: 80px 0; text-align: center; }
        .cta h2 { font-weight: 700; margin-bottom: 30px; }

        /* ===== Footer ===== */
        .footer { background-color: #343a40; color: white; padding: 50px 0 20px; }
        .footer-title { font-weight: 600; margin-bottom: 20px; color: #fff; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; }
        .footer-links a:hover { color: white; text-decoration: none; }
        .social-links { display: flex; gap: 15px; }
        .social-links a { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.1); color: white; transition: all 0.3s ease; }
        .social-links a:hover { background-color: #007bff; }
        .copyright { margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); text-align: center; color: rgba(255,255,255,0.5); }
    </style>

    @stack('styles')
</head>
<body>
    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 