@php
    $title = __('general.homepage') . ' | ' . __('general.website_name');
    $description = __('general.description');
@endphp

@extends('public.layout.app', [
    'title' => $title,
    'description' => $description,
])

@section('content')
    {{-- Banner --}}
    @if ($slides->count() > 0)
        <div class="banner home-banner">
            <div class="swiper">
                <div class="swiper-wrapper">
                    @foreach ($slides as $slide)
                        <div class="swiper-slide">
                            <div class="banner-slide position-relative">
                                <img class="slide-img" src="{{ getImgFromPath($slide->image) }}" alt="{{ $slide->translate()->title }}">
                                <div class="slide-text position-absolute top-0 bottom-0 start-0 end-0 d-flex align-items-center justify-content-center text-white fw-bold fs-4">
                                    {{ $slide->translate()->title }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

{{-- import styles --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
@endpush

{{-- import scripts --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const bannerSwiper = new Swiper(".banner .swiper", {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });
    </script>
@endpush