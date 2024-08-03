@extends('admin.layout.app', [
    'title' => $sliderTrans->title
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
                <div class="header-title">تفاصيل السلايد</div>
                <a href="{{ route('admin.sliders.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">العنوان</div>
                <div class="show-field-value">{{ $sliderTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصورة</div>
                <div class="show-field-value">
                    @if (!is_null($slider->image))
                        <img src="{{ asset('storage/'.$slider->image) }}" alt="">
                    @else
                        لا يوجد صورة
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الترتيب</div>
                <div class="show-field-value">{{ $slider->order }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">مكان السلايد</div>
                <div class="show-field-value">
                    @switch($slider->slider_location)
                        @case(1)
                            الصفحة الرئيسية
                            @break
                    @endswitch
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($slider->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($slider->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edite-slider')
                <a href="{{ route('admin.sliders.edit', [$slider, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection