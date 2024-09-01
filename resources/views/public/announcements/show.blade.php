@php
    $title = $itemTrans->title;
    $excerpt = $itemTrans->excerpt;
    $content = $itemTrans->body;
    $description = $excerpt ??  Str::limit(html_entity_decode(strip_tags($content)), 300);
    $image = getImgFromPath($item->image);
    $thumbnail = getImgThumbnail($item->image);
    $deadline = $item->deadline;
    $applyLink = $item->apply_link;
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $thumbnail
])

@section('content')
    <div class="ad-page-single">
        <div class="container my-5">
            {{-- Main title --}}
            <h1 class="page-title">{{ $title }}</h1>
            <div>
                {{-- Date --}}
                <b>{{ __('app.publish_date') }}: </b>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('Do MMMM YYYY') }}
                <br>
                {{-- Deadline --}}
                <b>{{ __('app.deadline') }}: </b>{{ Carbon\Carbon::parse($item->deadline)->isoFormat('Do MMMM YYYY') }}
                <br>
                {{-- Category --}}
                @if ($item->category && !is_null($category = $item->category->translate(app()->getLocale(), true)))
                    <b>{{ __('app.category') }}: </b>{{ $category->title }}
                @endif
            </div>
            {{-- Featured image --}}
            <div class="featured-img mt-3 mb-4">
                <img src="{{ $image }}" alt="{{ $title }}">
            </div>
            {{-- Body content --}}
            <div class="body-container">
                {!! $content !!}
            </div>
            {{-- Apply link --}}
            @if (!is_null($item->apply_link))
                <a href="{{ $item->apply_link }}" class="btn btn-primary">
                    {{ __('app.apply_link') }}
                </a>
            @endif
        </div>

        {{-- Related items --}}
        @if ($related->count() > 0)
            <section class="related-items my-5">
                <div class="container">
                    <h3 class="related-items">{{ __('pages.more_stories') }}</h3>
                    <div class="row row-gap-5">
                        @foreach ($related as $item)
                            <article class="col-md-4">
                                @php
                                    $itemText = $item->translate()->excerpt ?? html_entity_decode(strip_tags($item->translate()->body))
                                @endphp
                                <x-cards.basic-card
                                    :title="$item->translate()->title"
                                    :text="Str::limit($itemText, 300)"
                                    :img="getImgFromPath($item->image)"
                                    :link="route('stories.show', $item)"
                                    :date="$item->created_at"
                                />
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection