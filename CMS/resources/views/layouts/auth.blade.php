

<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Powells Automotive - Auth</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')

    <style>
        html, body, #app {
            height: 100%;
            margin: 0;
        }

        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .auth-container {
            width: 100%;
            padding: 0;
        }
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 12px; 
        }
    </style>
</head>
<body>
    <div id="app">
        <main class="auth-wrapper">
            <div class="auth-container">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
