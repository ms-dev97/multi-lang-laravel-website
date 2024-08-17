<div class="card basic-card">
    <img src="{{ $img }}" alt="{{ $title }}">
    <div class="card-body">

        @if (isset($date))
            <div class="date">
                <time datetime="{{ $date }}">
                    {{ Carbon\Carbon::parse($date)->isoFormat('Do MMMM YYYY') }}
                </time>
            </div>
        @endif

        <h4 class="card-title">{{ $title }}</h4>
        <p class="card-text">{{ $text }}</p>
        <a href="{{ $link }}" class="btn btn-primary">{{ __('app.read-more') }}</a>
    </div>
</div>