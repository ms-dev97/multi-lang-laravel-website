<div class="card ad-card position-relative">
    <a href="{{ $link }}">
        <img src="{{ $img }}" alt="{{ $title }}" class="card-img-top">
    </a>
    <div class="card-body">

        @if (isset($date))
            <x-date :date="$date" />
        @endif

        <h4 class="card-title">{{ $title }}</h4>
        <p class="card-text">{{ $text }}</p>
        <a href="{{ $link }}" class="btn btn-primary">{{ __('app.read-more') }}</a>
    </div>

    @isset($deadline)
        <div class="deadline fw-normal position-absolute badge text-bg-primary">
            <time datetime="{{ $deadline }}">
                {{ Carbon\Carbon::parse($deadline)->isoFormat('Do MMMM YYYY') }}
            </time>
        </div>        
    @endisset
</div>