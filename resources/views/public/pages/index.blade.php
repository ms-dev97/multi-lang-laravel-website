@php
    $title = $page->translate(app()->getLocale(), true)->name;
    $excerpt = $page->translate(app()->getLocale(), true)->excerpt;
    $content = $page->translate(app()->getLocale(), true)->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $image = getImgFromPath($page->image);
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $image ?? getImgFromPath(setting('header_logo')),
])

@section('content')
    <div class="container">
        <h1 class="page-title text-center mb-5">{{ $title }}</h1>

        <div class="featured-img mt-3 mb-4">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-100">
        </div>

        <div class="body-content">
            {!! $content !!}
        </div>
    </div>
@endsection