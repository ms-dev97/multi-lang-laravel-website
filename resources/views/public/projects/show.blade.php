@php
    $title = $itemTrans->title;
    $excerpt = $itemTrans->excerpt;
    $content = $itemTrans->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $gallery = $item->gallery;
    $image = getImgFromPath($item->image);
    $thumbnail = getImgThumbnail($item->image);
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $thumbnail
])

@section('content')
    <div class="projects-page-single">
        <main>
            {{-- Page banner --}}
            <div class="page-banner">
                <img src="{{ $image }}" alt="{{ $title }}" class="banner-img">
                <h1 class="banner-text">{{ $title }}</h1>
            </div>

            <div class="container my-5">
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
    </div>
@endsection