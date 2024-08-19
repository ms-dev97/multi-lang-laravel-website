<div class="card basic-card">
    <img src="{{ $img }}" alt="{{ $title }}" class="card-img-top">
    <div class="card-body">

        @if (isset($date))
            <x-date :date="$date" />
        @endif

        <h4 class="card-title">{{ $title }}</h4>
        <p class="card-text">{{ $text }}</p>
        <a href="{{ $link }}" class="btn btn-primary">{{ __('app.read-more') }}</a>
    </div>
</div>