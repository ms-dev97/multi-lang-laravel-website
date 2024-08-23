<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-logo">
                    <img src="{{ getImgFromPath(setting('footer_logo')) }}" alt="{{ __('general.website_name') }}">
                </div>

                <div class="social-icons d-flex gap-3 gap-3">
                    <a href="{{ setting('facebook_link') }}" class="icon" target="_blank">
                        <img src="{{ Vite::asset('resources/images/icons/facebook.svg') }}" alt="facebook">
                    </a>

                    <a href="{{ setting('twitter_link') }}" class="icon" target="_blank">
                        <img src="{{ Vite::asset('resources/images/icons/twitter.svg') }}" alt="twitter">
                    </a>

                    <a href="{{ setting('instagram_link') }}" class="icon" target="_blank">
                        <img src="{{ Vite::asset('resources/images/icons/instagram.svg') }}" alt="instagram">
                    </a>

                    <a href="{{ setting('linkedin_link') }}" class="icon" target="_blank">
                        <img src="{{ Vite::asset('resources/images/icons/linkedin.svg') }}" alt="linkedin">
                    </a>
                </div>
            </div>

            <div class="col-md-2">
                <h4 class="footer-title">{{ __('pages.about_us') }}</h4>

                <div class="footer-link">
                    <a href="/">{{ __('pages.about_org') }}</a>
                </div>

                <div class="footer-link">
                    <a href="/">{{ __('pages.our_partners') }}</a>
                </div>
            </div>

            <div class="col-md-2">
                <h4 class="footer-title">{{ __('pages.programs') }}</h4>

                @foreach (getPrograms() as $program)
                    <div class="footer-link">
                        <a href="{{ route('programs.show', $program) }}">
                            {{ $program?->translate()?->title }}
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="col-md-2">
                <h4 class="footer-title">{{ __('pages.contact_us') }}</h4>

                <div class="contact-info">
                    <div class="info">77554457</div>
                    <div class="info">ngo@mail.org</div>
                </div>
            </div>
        </div>
    </div>
</footer>