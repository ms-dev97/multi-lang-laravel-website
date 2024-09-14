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
                <ul class="navbar-nav justify-content-end align-items-center me-auto">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" @class(['nav-link', 'active' => Route::is('home')])>
                            {{ __('pages.home') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('programs.index') }}" @class(['nav-link', 'active' => Route::is('programs.*')])>
                            {{ __('pages.programs') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('news.index') }}" @class(['nav-link', 'active' => Route::is('news.*')])>
                            {{ __('pages.news') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('projects.index') }}" @class(['nav-link', 'active' => Route::is('projects.*')])>
                            {{ __('pages.projects') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('stories.index') }}" @class(['nav-link', 'active' => Route::is('stories.*')])>
                            {{ __('pages.success_stories') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('announcements.index') }}" @class(['nav-link', 'active' => Route::is('announcements.*')])>
                            {{ __('pages.announcements') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('galleries.index') }}" @class(['nav-link', 'active' => Route::is('galleries.*')])>
                            {{ __('pages.photo_gallery') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('documents.index') }}" @class(['nav-link', 'active' => Route::is('documents.*')])>
                            {{ __('pages.documents') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('videos.index') }}" @class(['nav-link', 'active' => Route::is('videos.*')])>
                            {{ __('pages.videos') }}
                        </a>
                    </li>

                    <div class="search-wrapper">
                        <form action="{{ route('search.index') }}">
                            <input
                                type="search"
                                name="q"
                                id="search"
                                class="search"
                                placeholder="{{ __('app.search') }}..."
                                list="search-suggestions"
                                autocomplete="off"
                            >
                        </form>
                    </div>

                    @php
                        $langLink = $lang == 'ar' ? LaravelLocalization::getLocalizedURL('en', null, [], true) : LaravelLocalization::getLocalizedURL('ar', null, [], true);
                    @endphp
                    <a href="{{ $langLink }}" class="lang-switcher bg-primary text-white mt-lg-0 mt-3">
                        {{ $lang == 'ar' ? 'EN' : 'AR' }}
                    </a>
                </ul>
            </div>
        </div>
    </div>
</nav>