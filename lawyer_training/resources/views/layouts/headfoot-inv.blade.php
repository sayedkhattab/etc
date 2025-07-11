<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investor Dashboard</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .header {
            background-color: #f8f9fa;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .header .logo-home img {
            max-height: 50px;
        }
        .header .home-menu ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }
        .header .home-menu a {
            color: #333;
            text-decoration: none;
            padding: 10px 15px;
        }
        .header .home-menu a:hover {
            background-color: #e9ecef;
            border-radius: 5px;
        }
        .header .toggle-dark-mode {
            margin-left: auto;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div>
      
        <main class="d-flex">
            @yield('content')
        </main>
    </div>
</body>
</html>
