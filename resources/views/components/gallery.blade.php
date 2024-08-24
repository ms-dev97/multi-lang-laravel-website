<section class="gallery-section mt-4">
    <h2 class="page-section-title mb-3">{{ $title ?? __('pages.gallery') }}</h2>

    <div class="gallery-wrapper row row-gap-4">
        @foreach ($gallery as $photo)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <img src="{{ $photo }}" alt="" class="gallery-img">
            </div>
        @endforeach
    </div>

    <div class="gallery-viewer" id="gallery-viewer" popover>
        <div class="close"></div>
        <img class="viewer-img" src="" alt="">
        <div class="navigation">
            <div class="next">next</div>
            <div class="pre">previous</div>
        </div>
    </div>
</section>