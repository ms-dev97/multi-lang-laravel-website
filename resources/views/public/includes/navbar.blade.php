<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a href="{{ route('home') }}" class="navbar-brand">
            @if (URL::current() == route('home'))
                {{-- if the main page only / this is for SEO --}}
                <h1 class="visually-hidden">{{ __('general.website_name') }}</h1>
            @endif
            <img src="{{ getImgFromPath(setting('header_logo')) }}" alt="{{ __('general.website_name') }}" width="150">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" id="main-nav">
            <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('app.close') }}"></button>
            </div>

            <div class="offcanas-body">
                <ul class="navbar-nav justify-content-end me-auto">
                    <li class="nav-item">
                        <a href="/" class="nav-link">الرئيسية</a>
                    </li>

                    <li class="nav-item">
                        <a href="/" class="nav-link">البرامج</a>
                    </li>

                    <li class="nav-item">
                        <a href="/" class="nav-link">الاخبار</a>
                    </li>

                    <li class="nav-item">
                        <a href="/" class="nav-link">المشاريع</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>