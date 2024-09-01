@php
    $title = $item->translate()->title;
    $excerpt = $item->translate()->excerpt;
    $content = $item->translate()->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $photos = $item->photos;
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $photos[0]
])

@section('content')
    <div class="gallery-page-single">
        <div class="container my-5">
            {{-- Main title --}}
            <h1 class="page-title">{{ $title }}</h1>
            {{-- Date --}}
            <x-date :date="$item->created_at" />
            {{-- Body content --}}
            <div class="body-container">
                {!! $content !!}
            </div>
            {{-- Phoros --}}
            @if (!empty($photos))
                <section class="gallery-section mt-4">
                    <div class="gallery-wrapper row row-gap-4">
                        @foreach ($photos as $photo)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <img src="{{ $photo }}" alt="" class="gallery-img">
                            </div>
                        @endforeach
                    </div>

                    <div class="gallery-viewer" id="gallery-viewer" popover>
                        <div class="close"></div>
                        <img class="viewer-img" src="" alt="">
                        <div class="navigation">
                            <div class="next">
                                <svg width="16" height="28" viewBox="0 0 16 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.2387 15.346L3.47146 27.0803C2.72293 27.8271 1.5093 27.8271 0.761127 27.0803C0.0128925 26.3341 0.0128925 25.1239 0.761127 24.3778L11.1733 13.9948L0.76143 3.61216C0.0131954 2.86569 0.0131954 1.65562 0.76143 0.909456C1.50966 0.162994 2.72323 0.162994 3.47177 0.909456L15.239 12.6439C15.6131 13.0171 15.8 13.5058 15.8 13.9947C15.8 14.4839 15.6127 14.9729 15.2387 15.346Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <div class="pre">
                                <svg width="16" height="28" viewBox="0 0 16 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.761314 15.346L12.5285 27.0803C13.2771 27.8271 14.4907 27.8271 15.2389 27.0803C15.9871 26.3341 15.9871 25.1239 15.2389 24.3778L4.8267 13.9948L15.2386 3.61216C15.9868 2.86569 15.9868 1.65562 15.2386 0.909456C14.4903 0.162994 13.2768 0.162994 12.5282 0.909456L0.761013 12.6439C0.386895 13.0171 0.200048 13.5058 0.200048 13.9947C0.200048 14.4839 0.387259 14.9729 0.761314 15.346Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection