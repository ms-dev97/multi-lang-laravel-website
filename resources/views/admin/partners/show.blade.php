@extends('admin.layout.app', [
    'title' => $partnerTrans->title
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
                <div class="header-title">تفاصيل الشريك</div>
                <a href="{{ route('admin.partners.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $partnerTrans->name }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصورة</div>
                <div class="show-field-value">
                    @if (!is_null($partner->image))
                        <img src="{{ asset('storage/'.$partner->image) }}" alt="">
                    @else
                        لا يوجد صورة
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الترتيب</div>
                <div class="show-field-value">{{ $partner->order }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الرابط</div>
                <div class="show-field-value">{{ $partner->link ?? 'لا يوجد' }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($partner->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($partner->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edite-partner')
                <a href="{{ route('admin.partners.edit', [$partner, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection