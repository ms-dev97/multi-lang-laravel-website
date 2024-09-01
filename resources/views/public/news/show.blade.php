@php
    $title = $item->translate()->title;
    $excerpt = $item->translate()->excerpt;
    $content = $item->translate()->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $image = getImgFromPath($item->image);
    $thumbnail = getImgThumbnail($item->image);
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $thumbnail
])

@section('content')
    <div class="news-page-single">
        <div class="container">
            {{-- Main title --}}
            <h1 class="page-title">{{ $title }}</h1>
            {{-- Date --}}
            <x-date :date="$item->created_at" />
            {{-- Featured image --}}
            <div class="featured-img mt-3 mb-4">
                <img src="{{ $image }}" alt="{{ $title }}">
            </div>
            {{-- Body content --}}
            <div class="body-container">
                {!! $content !!}
            </div>
        </div>

        {{-- Related items --}}
        @if ($related->count() > 0)
            <section class="related-items my-5">
                <div class="container">
                    <h3 class="related-items">{{ __('pages.more_news') }}</h3>
                    <div class="row row-gap-5">
                        @foreach ($related as $item)
                            <article class="col-md-4">
                                @php
                                    $newsText = $item->translate()->excerpt ?? Str::limit(html_entity_decode(strip_tags($item->translate()->body)), 300)
                                @endphp
                                <x-cards.basic-card
                                    :title="$item->translate()->title"
                                    :text="Str::limit($newsText, 300)"
                                    :img="getImgFromPath($item->image)"
                                    :link="route('news.show', $item)"
                                    :date="$item->created_at"
                                />
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection