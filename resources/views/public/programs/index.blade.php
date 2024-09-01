@php
    $title = __('pages.programs');
    $description = __('pages.programs_description');
    $image = getImgFromPath(setting('header_logo'));
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $image
])

@section('content')
    <div class="programs-page">
        <div class="container">
            <h1 class="page-title">{{ $title }}</h1>

            <section class="row row-gap-5 mb-5">
                @forelse ($programs as $item)
                    <article class="col-md-4">
                        <x-cards.overlay-card
                            :title="$item->translate(app()->getLocale(), true)->title"
                            :cover="getImgThumbnail($item->image)"
                            :icon="getImgFromPath($item->icon)"
                            :link="route('programs.show', $item)"
                        />
                    </article>
                @empty
                    @include('public.includes.no-data')
                @endforelse
            </section>

            {{ $programs->withQueryString()->links('pagination::default') }}
        </div>
    </div>
@endsection