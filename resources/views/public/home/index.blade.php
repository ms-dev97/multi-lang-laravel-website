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

    {{-- News --}}
    @if ($news->count() > 0)
        <section class="section news my-5">
            <div class="container">
                <h2 class="section-title mb-3">{{ __('pages.news') }}</h2>
                <div class="row row-gap-5">
                    @foreach ($news as $item)
                        @php
                            $newsText = $item->translate()->excerpt ?? strip_tags($item->translate()->body)
                        @endphp
                        <div class="col-md-4">
                            <x-cards.basic-card
                                :title="$item->translate()->title"
                                :text="Str::limit($newsText, 300)"
                                :img="getImgFromPath($item->image)"
                                :link="route('news.show', $item)"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Programs --}}
    @if ($programs->count() > 0)
        <section class="section programs my-5">
            <div class="container">
                <h2 class="section-title mb-3">{{ __('pages.programs') }}</h2>
                <div class="row row-gap-5">
                    @foreach ($programs as $program)
                        <div class="col-md-4">
                            <x-cards.overlay-card
                                :title="$program->translate()->title"
                                :cover="getImgFromPath($program->cover)"
                                :icon="getImgFromPath($program->icon)"
                                :link="route('programs.show', $program)"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
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