<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .welcome-laravel {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-welcome {
            font-size: 1.5rem;
            font-family: 'Roboto', sans-serif;
            margin: 0 1rem;
            text-decoration: none;
            color: #1a202c;
            font-weight: 600;
            margin-inline: 1rem;
        }

        /* ! tailwindcss v3.2.4 | MIT License | https://tailwindcss.com */
    </style>
</head>

<body class="antialiased">
    <div class="welcome-laravel">

        @if (Route::has('login'))
        <div class="">
            @auth
            <a href="{{ url('/dashboard') }}" class="login-welcome">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="login-welcome">Log in</a>
            @endauth
        </div>
        @endif



    </div>

</body>

</html>