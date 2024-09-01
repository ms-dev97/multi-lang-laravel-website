@php
    $title = $itemTrans->title;
    $excerpt = $itemTrans->excerpt;
    $content = $itemTrans->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $image = $item->image ?? $item->vidImage();
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $image
])

@section('content')
    <div class="videos-page-single">
        <div class="container my-5">
            {{-- Main title --}}
            <h1 class="page-title">{{ $title }}</h1>
            {{-- Date --}}
            <x-date :date="$item->created_at" />
            {{-- Video frame --}}
            <div class="my-4">
                {!! $item->vidFrame() !!}
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
                    <h3 class="related-items">{{ __('pages.more_videos') }}</h3>
                    <div class="row row-gap-5">
                        @foreach ($related as $item)
                            <article class="col-md-4">
                                @php
                                    $itemText = $item->translate(app()->getLocale(), true)->excerpt ?? html_entity_decode(strip_tags($item->translate(app()->getLocale(), true)->body))
                                @endphp
                                <x-cards.basic-card
                                    :title="$item->translate(app()->getLocale(), true)->title"
                                    :text="Str::limit($itemText, 300)"
                                    :img="getImgFromPath($item->image)"
                                    :link="route('videos.show', $item)"
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