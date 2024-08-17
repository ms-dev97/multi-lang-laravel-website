<div class="card basic-card">
    <img src="{{ $img }}" alt="{{ $title }}">
    <div class="card-body">
        <h4 class="card-title">{{ $title }}</h4>
        <p class="card-text">{{ $text }}</p>
        <a href="{{ $link }}" class="btn btn-primary">{{ __('app.read-more') }}</a>
    </div>
</div>