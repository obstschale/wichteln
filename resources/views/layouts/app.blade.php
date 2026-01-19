<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
    <footer class="py-6 text-center text-gray-600">
        <p>
            <a href="{{ url('/imprint') }}" class="hover:underline">Impressum</a> |
            <a href="{{ url('/privacy') }}" class="hover:underline">Datenschutzerklärung</a>
        </p>
    </footer>
</body>
</html>
