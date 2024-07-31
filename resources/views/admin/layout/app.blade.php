<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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