@php
    $title = __('pages.announcements');
    $description = __('pages.announcements_description');
    $image = getImgFromPath(setting('header_logo'));
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $image
])

@section('content')
    <main class="ad-page">
        <div class="container">
            <h1 class="page-title">{{ $title }}</h1>

            <section class="row row-gap-5 mb-5">
                @forelse($announcements as $item)
                    <article class="col-md-4">
                        @php
                            $itemText = $item->translate()->excerpt ?? html_entity_decode(strip_tags($item->translate()->body));
                        @endphp

                        <x-cards.ad-card
                            :title="$item->translate()->title"
                            :text="Str::limit($itemText, 300)"
                            :img="getImgThumbnail($item->image)"
                            :link="route('announcements.show', $item)"
                            :date="$item->created_at"
                            :deadline="$item->deadline"
                        />
                    </article>
                @empty
                    @include('public.includes.no-data')
                @endforelse
            </section>

            {{ $announcements->withQueryString()->links('pagination::default') }}
        </div>
    </main>
@endsection