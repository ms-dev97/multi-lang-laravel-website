<a href="{{ $link }}" class="card overlay-card position-relative">
    <div class="cover">
        <img src="{{ $cover }}" alt="{{ $title }}">
    </div>

    <div class="card-front position-absolute top-0 bottom-0 start-0 end-0 text-white d-flex flex-column align-items-center justify-content-center">
        <img src="{{ $icon }}" alt="icon" class="icon">
        <h4 class="card-title">{{ $title  }}</h4>
    </div>
</a>