@extends('admin.layout.app', [
    'title' => $newsTrans->title
])

@section('main')
    <div class="notifications">
        @session('success')
            @include('admin.partials.notification', [
                'text' => session('success'),
                'type' => 'success'    
            ])
        @endsession

        @session('warning')
            @include('admin.partials.notification', [
                'text' => session('warning'),
                'type' => 'danger'    
            ])
        @endsession

        @session('error')
            @include('admin.partials.notification', [
                'text' => session('error'),
                'type' => 'danger'    
            ])
        @endsession
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex justify-content-between align-items-center">
                <div class="header-title">معلومات الخبر</div>
                <a href="{{ route('admin.categories.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">العنوان</div>
                <div class="show-field-value">{{ $newsTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصورة</div>
                <div class="show-field-value">
                    <img src="{{ asset('storage/'.$news->image) }}" alt="">
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{!! $newsTrans->body !!}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الأقسام</div>
                <div class="show-field-value">
                    <ul>
                        @forelse ($categories as $cat)
                            <li>{{ $cat->translate($currentLang, true)->title }}</li>
                        @empty
                            لا يوجد
                        @endforelse
                    </ul>
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">معرض الصور</div>
                <div class="flex g-0.5rem show-gallery">
                    @forelse ($news->gallery as $item)
                        <img class="gallery-img" src="{{ $item }}" class="gallery-img">
                    @empty
                        لا توجد صور
                    @endforelse
                </div>
            </div>

            @can('edite-news')
                <a href="{{ route('admin.news.edit', [$news, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection