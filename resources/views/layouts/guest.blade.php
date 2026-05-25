<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BarberTime') }}</title>

    <!-- Google font for auth pages -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('site/css/auth.css') }}">
</head>
<body>
    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.auth-password-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    var targetId = button.getAttribute('data-password-target');
                    var input = document.getElementById(targetId);

                    if (!input) {
                        return;
                    }

                    var isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.classList.toggle('is-visible', isPassword);
                    button.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                    button.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                });
            });
        });
    </script>
</body>
</html>
