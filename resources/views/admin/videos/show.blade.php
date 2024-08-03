@extends('admin.layout.app', [
    'title' => $videoTrans->title
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
                <div class="header-title">تفاصيل الفيديو</div>
                <a href="{{ route('admin.videos.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $videoTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">رابط اليوتيوب</div>
                <div class="show-field-value">{{ $video->link }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الوصف المختصر</div>
                <div class="show-field-value">{{ $videoTrans->excerpt ?? 'لا يوجد' }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{!! $videoTrans->body ?? 'لا يوجد' !!}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($video->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($video->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edite-video')
                <a href="{{ route('admin.videos.edit', [$video, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection