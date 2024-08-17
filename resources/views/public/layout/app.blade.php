<!DOCTYPE html>
<html lang="{{ $lang = App::getLocale() }}" dir="{{ $langDir = LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Meta --}}
    <meta name="description" lang="{{ $lang }}" content="{{ $description ?? __('general.description') }}">
    <meta name="robots" content="index, follow">

    {{-- facebook and twitter metatags --}}
    {{-- open graph --}}
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:url" content="{{ $url = LaravelLocalization::getLocalizedURL($lang, url()->current()) }}" />
    <meta property="og:type" content="{{ $ogtype ?? 'website' }}" />
    <meta property="og:site_name" content="{{ __('general.website_name') }}">
    <meta property="og:description" content="{{ $description ?? __('general.description') }}" />
    <meta property="og:image" content="{{ $metaImage ?? setting('header_logo') }}" />
    <meta property="fb:app_id" content="{{ setting('facebook_id') ?? '' }}" />

    {{-- twitter summary card --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="{{ setting('twitter_id') ?? '' }}" />
    <meta name="twitter:title" content="{{ $title }}" />
    <meta name="twitter:description" content="{{ $description ?? __('general.description') }}" />
    <meta name="twitter:image" content="{{ $metaImage ?? setting('header_logo') }}" />

    {{-- canonical and alternative --}}
    <link rel="canonical" href="{{ $url }}">
    <link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL(config('app.locale'), url()->current()) }}" hreflang="x-default">
    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL($localeCode, url()->current()) }}" hreflang="{{ $localeCode }}">
    @endforeach

    {{-- Styles --}}
    @vite(['resources/sass/app.scss'])

    {{-- Push css styles --}}
    @stack('styles')

    {{-- favicons --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="{{ __('general.title') }}">
    <meta name="application-name" content="{{ __('general.title') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    {{-- Google tag (gtag.js) --}}
    @php
        $googleTag = "setting('google_tag')";
    @endphp
    @if ($googleTag)
        <script async
            src="https://www.googletagmanager.com/gtag/js?id={{ $googleTag }}">
        </script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $googleTag }}');
        </script>
    @endif

    <title>{{ $title ?? '⚠️ Change the Title' }}</title>
</head>
<body>
    {{-- for accessibility --}}
    <div class="d-flex position-fixed mx-auto start-0 end-0 justify-content-center z-3">
        <a class="visually-hidden-focusable py-3 px-4 bg-white text-center" href="#main-content">{{ __('app.skip-to-main') }}</a>
    </div>

    {{-- header --}}
    @include('public.layout.header')

    <main id="main-content" class="main-content">
        @yield('content')
    </main>

    {{-- footer --}}
    @include('public.layout.footer')

    {{-- Scripts --}}
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>