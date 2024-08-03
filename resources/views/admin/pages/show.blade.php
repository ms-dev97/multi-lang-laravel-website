@extends('admin.layout.app', [
    'title' => $pageTrans->title
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
                <div class="header-title">تفاصيل الصفحة</div>
                <a href="{{ route('admin.pages.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $pageTrans->name }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">اسم الرابط</div>
                <div class="show-field-value">{{ $page->slug }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصورة</div>
                <div class="show-field-value">
                    @if (!is_null($page->image))
                        <img src="{{ asset('storage/'.$page->image) }}" alt="">
                    @else
                        لا يوجد صورة
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">excerpt</div>
                <div class="show-field-value">{{ $pageTrans->excerpt }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{!! $pageTrans->body !!}</div>
            </div>

            <hr>

            @role('super-admin')
                <div class="show-field">
                    <div class="show-field-name">فيو مخصص</div>
                    <div class="show-field-value">{{ $page->has_custom_view ? 'نعم' : 'لا' }}</div>
                </div>

                <hr>

                <div class="show-field">
                    <div class="show-field-name">اسم الفيو</div>
                    <div class="show-field-value">{{ $page->view_name ?? 'لا يوجد' }}</div>
                </div>

                <hr>
            @endrole

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($page->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($page->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edite-page')
                <a href="{{ route('admin.pages.edit', [$page, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection