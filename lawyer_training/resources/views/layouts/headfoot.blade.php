
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Real Estate Investment</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> <!-- Modern font -->
    <style>
        body {
            background-color: #f0f0f0; /* لون خلفية فاتح */
            color: #333; /* لون النص */
        }
        .header {
            background-color: #ffffff; /* لون خلفية الهيدر فاتح */
            color: #333; /* لون النص */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .home-menu ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }
        .home-menu a {
            color: #333;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }
        .home-menu a:hover {
            background-color: #ddd;
        }
        .nav-links a {
            color: #333;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }
        .nav-links a:hover {
            background-color: #ddd;
        }
        .footer {
            background-color: #ffffff; /* لون خلفية الفوتر فاتح */
            color: #333; /* لون النص */
            text-align: center;
            padding: 20px;
            box-shadow: 0px -2px 4px rgba(0, 0, 0, 0.1);
        }
        h1, p {
            color: #333; /* لون النص */
        }
        #countdown {
            color: #e3342f; /* لون العداد */
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div>
        <header class="header">
            <div class="logo-home">
                <img src="{{ asset('images/logo4.png') }}" alt="Real Estate Investment">
            </div>
            <div class="home-menu">
                <ul class="menuu">
                    <li><a href="/">Home</a></li>
                    <li><a href="#">developers</a></li>
                    <li><a href="#">Investors</a></li>
                    <li><a href="#">Projects</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            
            <nav class="nav-links">
                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Log out</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register.developer') }}">Register as Developer</a>
                            <a href="{{ route('register.investor') }}">Register as Investor</a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>
        <main>
            @yield('content')
        </main>
        <footer class="footer">
            <p>All rights reserved © 2024 Real Invest</p>
        </footer>
    </div>
</body>
</html>
