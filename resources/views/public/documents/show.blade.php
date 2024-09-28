@php
    $title = $itemTrans->title;
    $excerpt = $itemTrans->excerpt;
    $content = $itemTrans->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $image = getImgFromPath($item->image);
    $thumbnail = getImgThumbnail($item->image);
    $file = !is_null($item->path) ? asset('storage/'.$item->path) : $item->link;
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $thumbnail
])

@section('content')
    <div class="document-page-single">
        <div class="container my-5">
            {{-- Main title --}}
            <h1 class="page-title">{{ $title }}</h1>
            {{-- Featured image --}}
            <div class="featured-img mt-3 mb-4">
                <img src="{{ $image }}" alt="{{ $title }}" class="w-100">
            </div>
            {{-- Body content --}}
            <div class="body-container">
                {!! $content !!}
            </div>
            {{-- download link --}}
            @if ($file)
                <a href="{{ $file }}" class="btn btn-primary" download>
                    {{ __('app.download') }}
                </a>
            @endif
        </div>
    </div>
@endsection