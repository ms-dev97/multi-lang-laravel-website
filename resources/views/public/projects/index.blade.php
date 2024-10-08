@php
    $title = __('pages.projects');
    $description = __('pages.projects_description');
    $image = getImgFromPath(setting('header_logo'));
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $image
])

@section('content')
    <div class="projects-page">
        <div class="container">
            <h1 class="page-title">{{ $title }}</h1>

            <section class="row row-gap-5 mb-5">
                @forelse ($projects as $item)
                    <article class="col-md-4">
                        @php
                            $itemText = $item->translate(app()->getLocale(), true)->excerpt ?? Str::limit(html_entity_decode(strip_tags($item->translate(app()->getLocale(), true)->body)), 300)
                        @endphp
                        <x-cards.basic-card
                            :title="$item->translate(app()->getLocale(), true)->title"
                            :text="Str::limit($itemText, 300)"
                            :img="getImgThumbnail($item->image)"
                            :link="route('projects.show', $item)"
                        />
                    </article>
                @empty
                    @include('public.includes.no-data')
                @endforelse
            </section>

            {{ $projects->withQueryString()->links('pagination::default') }}
        </div>
    </div>
@endsection