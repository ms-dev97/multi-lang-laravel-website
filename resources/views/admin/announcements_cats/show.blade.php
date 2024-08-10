@extends('admin.layout.app', [
    'title' => $categoryTrans->title
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
                'type' => 'warning'    
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
        <div class="card-title">
            <div class="flex justify-content-between align-items-center">
                <div class="header-title">معلومات القسم</div>
                <a href="{{ route('admin.announcement-categories.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
                @include('admin.partials.lang-select', [
                    'currentLang' => $currentLang,
                    'langs' => $langs,
                ])

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $categoryTrans->title }}</div>
            </div>

            @can('edit-ad-category')
                <a href="{{ route('admin.announcement-categories.edit', [$category, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection