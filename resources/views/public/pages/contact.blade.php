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
    <div class="contact-page">
        <h1 class="page-title text-center mb-5">{{ $title }}</h1>

        <form action="{{ route('contact_us.store') }}" class="contact-form mb-5" method="post">
            @csrf

            <div class="container">

                @session('success')
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession

                <div class="row row-gap-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">{{ __('app.name') }}</label>
                            <input
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="{{ __('app.name') }}"
                            >
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject">{{ __('app.subject') }}</label>
                            <input
                                class="form-control @error('subject') is-invalid @enderror"
                                id="subject"
                                type="text"
                                name="subject"
                                value="{{ old('subject') }}"
                                required
                                placeholder="{{ __('app.subject') }}"
                            >
                            @error('subject')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row row-gap-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">{{ __('app.phone') }}</label>
                            <input
                                class="form-control @error('phone') is-invalid @enderror"
                                id="phone"
                                type="tel"
                                name="phone_number"
                                value="{{ old('phone') }}"
                                placeholder="{{ __('app.phone') }}"
                            >
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">{{ __('app.email') }}</label>
                            <input
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="{{ __('app.email') }}"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="message">{{ __('app.message') }}</label>
                    <textarea
                        class="form-control @error('message') is-invalid @enderror"
                        id="message"
                        name="message"
                        required
                        placeholder="{{ __('app.message') }}"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- use captcha --}}
                {!! NoCaptcha::display() !!}

                @error('g-recaptcha-response')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="btn btn-primary mt-4">
                    {{ __('app.send') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {!! NoCaptcha::renderJs() !!}
@endpush