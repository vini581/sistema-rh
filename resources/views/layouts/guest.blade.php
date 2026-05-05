<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } } };
            (function() {
                var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                if (tz && document.cookie.indexOf('client_timezone=' + tz) === -1) {
                    document.cookie = 'client_timezone=' + tz + ';path=/;max-age=31536000;SameSite=Lax';
                }
                if (localStorage.getItem('darkMode') === 'true') {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; background-color: #fafafa; color: #000; }
            .dark body { background-color: #0a0a0a; color: #fff; }
            .card { background: #fff; border: 1px solid #eaeaea; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
            .dark .card { background: #000; border: 1px solid #333; }
        </style>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Smart" class="w-32 h-32 object-contain">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 card sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
