@php
    $title = __('pages.contact_us');
    $description = __('pages.contact_us_description');
    $image = getImgFromPath(setting('header_logo'));
@endphp

@extends('public.layout.app', [
    'title' => $title . ' | ' . __('general.website_name'),
    'description' => $description,
    'metaImage' => $image
])

@section('content')
    <main class="contact-page">
        <h1 class="page-title text-center mb-5">{{ $title }}</h1>

        <form action="" class="contact-form mb-5" method="post">
            @csrf

            <div class="container">
                <div class="row row-gap-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">{{ __('app.name') }}</label>
                            <input class="form-control" required type="text" name="name" placeholder="{{ __('app.name') }}" id="name">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject">{{ __('app.subject') }}</label>
                            <input class="form-control" required type="text" name="subject" placeholder="{{ __('app.subject') }}" id="subject">
                        </div>
                    </div>
                </div>

                <div class="row row-gap-4 mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">{{ __('app.phone') }}</label>
                            <input class="form-control" type="tel" name="phone" placeholder="{{ __('app.phone') }}" id="phone">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">{{ __('app.email') }}</label>
                            <input class="form-control" type="email" name="email" placeholder="{{ __('app.email') }}" id="email">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="message">{{ __('app.message') }}</label>
                    <textarea name="message" id="message" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ __('app.send') }}
                </button>
            </div>
        </form>
    </main>
@endsection