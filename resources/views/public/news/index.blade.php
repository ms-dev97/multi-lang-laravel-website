@php
    $title = __('pages.news');
    $description = __('pages.news_description');
    $image = getImgFromPath(setting('header_logo'));
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $image
])

@section('content')
    <main class="news-page">
        <div class="container">
            <h1 class="page-title">{{ $title }}</h1>

            <section class="row row-gap-5 mb-5">
                @forelse($news as $item)
                    <article class="col-md-4">
                        @php
                            $newsText = $item->translate()->excerpt ?? Str::limit(html_entity_decode(strip_tags($item->translate()->body)), 300)
                        @endphp

                        <x-cards.basic-card
                            :title="$item->translate()->title"
                            :text="Str::limit($newsText, 300)"
                            :img="getImgThumbnail($item->image)"
                            :link="route('news.show', $item)"
                            :date="$item->created_at"
                        />
                    </article>
                @empty
                    @include('public.includes.no-data')
                @endforelse
            </section>

            {{ $news->withQueryString()->links('pagination::default') }}
        </div>
    </main>
@endsection