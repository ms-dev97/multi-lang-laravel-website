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
    <div class="programs-page-single">
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

                {{-- Projects --}}
                @if($projects->count() > 0)
                    <section class="my-5">
                        <div class="container">
                            <h3 class="page-section-title">{{ __('pages.projects') }}</h3>
                            <div class="row row-gap-5">
                                @foreach ($projects as $item)
                                    <article class="col-md-4">
                                        @php
                                            $projectText = $item->translate()->excerpt ?? Str::limit(html_entity_decode(strip_tags($item->translate()->body)), 300)
                                        @endphp
                                        <x-cards.basic-card
                                            :title="$item->translate()->title"
                                            :text="Str::limit($projectText, 300)"
                                            :img="getImgFromPath($item->image)"
                                            :link="route('projects.show', $item)"
                                            :date="$item->created_at"
                                        />
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        </main>
    </div>
@endsection