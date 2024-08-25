@php
    $title = $itemTrans->title;
    $excerpt = $itemTrans->excerpt;
    $content = $itemTrans->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $image = getImgFromPath($item->image);
    $thumbnail = getImgThumbnail($item->image);
    $gallery = $item->gallery;
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $thumbnail
])

@section('content')
    <div class="stories-page-single">
        <main>
            <div class="container my-5">
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
                {{-- Gallery --}}
                @if (!empty($gallery))
                    <x-gallery :gallery="$gallery" />
                @endif
            </div>
        </main>

        {{-- Related items --}}
        @if ($related->count() > 0)
            <section class="related-items my-5">
                <div class="container">
                    <h3 class="related-items">{{ __('pages.more_stories') }}</h3>
                    <div class="row row-gap-5">
                        @foreach ($related as $item)
                            <article class="col-md-4">
                                @php
                                    $itemText = $item->translate()->excerpt ?? html_entity_decode(strip_tags($item->translate()->body))
                                @endphp
                                <x-cards.basic-card
                                    :title="$item->translate()->title"
                                    :text="Str::limit($itemText, 300)"
                                    :img="getImgFromPath($item->image)"
                                    :link="route('stories.show', $item)"
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