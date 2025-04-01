<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Powells Automotive</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div id="app" class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            @auth
            <div class="user-info-section">
                <div class="business-name">
                    <a href="{{ url('/home') }}"
                        style="text-decoration: none; color: inherit;"
                        onmouseover="this.style.textDecoration='underline';"
                        onmouseout="this.style.textDecoration='none';">
                            <h4>Powells Automotive</h4>
                        </a> 
                </div>
                <!-- Small Logo -->
                <div class="small-logo">
                    <a href="{{ url('/home') }}">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
                    </a>
                </div>
                <!-- User's Full Name -->
                <div class="user-name">
                    {{ Auth::user()->firstName }} {{ Auth::user()->lastName }}
                </div>
                <!-- Logout Button -->
                <div class="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            @endauth

            @guest
            <div class="user-info-section">
                <a href="{{ route('login') }}" style="color: #ffffff; text-decoration: none;">Login</a>
            </div>
            @endguest

            <!-- Navigation -->
            <ul class="nav-links">
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li><a href="{{ url('/pages') }}">Pages</a></li>
                <li><a href="{{ url('/sections') }}">Sections</a></li>
                <li><a href="{{ url('/users') }}">Users</a></li>
                <li><a href="{{ url('/customers') }}">Customers</a></li>
                <li><a href="{{ url('/vehicles') }}">Vehicles</a></li>
                <li><a href="{{ url('/calendar') }}">Calendar/Schedule</a></li>
              <!--  <li><a href="#">Tickets (not in prototype)</a></li>
                <li><a href="#">Invoices/Reports(not in prototype)</a></li>  -->
            </ul>
        </div>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div class="flex-grow-1">
            <div class="top-header">
                <h1 class="page-title">
                    @yield('pageTitle', 'Dashboard')
                </h1>
            </div>
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
