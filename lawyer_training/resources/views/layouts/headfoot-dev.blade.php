<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Developer dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <header class="header d-flex justify-content-between align-items-center p-3">
            <div class="logo-home">
                <img src="{{ asset('images/logo4.png') }}" alt="Logo" class="logo-home">
            </div>
            <div class="home-menu">
                <ul class="menuu d-flex list-unstyled mb-0">
                    <li class="mr-3"><a href="/developer/dashboard">Dashboard</a></li>
                    <li class="mr-3"><a href="/developer/projects">Projects List</a></li>
                    <li class="mr-3"><a href="/developer/projects/create">Create Project</a></li>
                    <li><a href="#">Profile</a></li>
             
                </ul>
            </div>

            <div class="toggle-dark-mode">
            <form method="POST" action="{{ route('logout') }}" style="
                    padding: 0px !important;
                    margin-top: 0px !important;
                    border-radius: 0px;
                    box-shadow: none;
                    max-width: 100%;
                    width: 100%;
                    margin: 0 auto;
                    ">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
            </div>
         
        </header>
        <main>
            @yield('content')
        </main>
        <footer class="footer">
            <p>All rights reserved © 2024 Real Invest</p>
        </footer>
    </div>
       <!-- jQuery -->
       <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
