<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <!-- Google Fonts - El Messiri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'El Messiri', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .login-container {
            max-width: 450px;
            width: 100%;
        }
        .login-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin: 0 auto;
        }
        .login-header {
            background-color: #3E3E3E;
            color: white;
            text-align: center;
            padding: 30px 20px;
            border-radius: 10px 10px 0 0;
        }
        .logo-img {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        .login-card-header {
            background-color: #AB8A47;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .login-form {
            padding: 30px;
        }
        .btn-login {
            background-color: #3E3E3E;
            border-color: #3E3E3E;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        .btn-login:hover, 
        .btn-login:focus, 
        .btn-login:active,
        .btn-login.active {
            background-color: #AB8A47 !important;
            border-color: #AB8A47 !important;
            color: white !important;
            box-shadow: none !important;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #3E3E3E;
        }
        .input-group {
            direction: ltr;
        }
        .form-control, .input-group-text {
            direction: ltr;
            text-align: left;
        }
        .text-gold {
            color: #AB8A47;
        }
        .password-toggle {
            cursor: pointer;
            padding: 0.375rem 0.75rem;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 0 0.25rem 0.25rem 0;
        }
        /* Override Bootstrap's default focus styles */
        .btn-primary:focus,
        .btn-primary.focus {
            color: white;
            background-color: #AB8A47 !important;
            border-color: #AB8A47 !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="card login-card">
                <div class="login-header">
                    <img src="{{ asset('images/logo.png') }}" alt="إثبات" class="logo-img">
                    <p class="mb-0">منصة التعليم القانوني والمحكمة الافتراضية</p>
                </div>
                
                <div class="login-form">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="login" class="form-label">البريد الإلكتروني أو اسم المستخدم</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autofocus>
                            </div>
                            @error('login')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                <span class="input-group-text password-toggle" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                تذكرني
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-login">
                                تسجيل الدخول
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html> 