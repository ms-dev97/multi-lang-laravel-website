@extends('admin.layout.app', [
    'title' => $galleryTrans->title
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
                <div class="card-title">تفاصيل معرض الصور</div>
                <a href="{{ route('admin.galleries.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">العنوان</div>
                <div class="show-field-value">{{ $galleryTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الوصف المختصر</div>
                <div class="show-field-value">{{ $galleryTrans->excerpt ?? 'لا يوجد' }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{!! $galleryTrans->body ?? 'لا يوجد' !!}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصور</div>
                <div class="flex g-0.5rem show-gallery">
                    @forelse ($gallery->photos as $item)
                        <img class="gallery-img" src="{{ $item }}" class="gallery-img">
                    @empty
                        لا توجد صور
                    @endforelse
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($gallery->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($gallery->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edite-gallery')
                <a href="{{ route('admin.galleries.edit', [$gallery, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection