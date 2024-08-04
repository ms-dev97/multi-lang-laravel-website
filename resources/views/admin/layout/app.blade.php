<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Beiruti:wght@200..900&family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    {{-- Main stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/main.css') }}" />
    @stack('styles')
    <title>{{ $title }}</title>
</head>
<body>
    @include('admin.layout.header')
    @include('admin.layout.sidebar')
    <main class="main">
        @yield('main')
    </main>
    @include('admin.layout.footer')

    <input type="checkbox" class="toggle-color-mode">

    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>