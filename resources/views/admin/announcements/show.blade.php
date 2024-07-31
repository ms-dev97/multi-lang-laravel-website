@extends('admin.layout.app', [
    'title' => $announcementTrans->title
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
        <div class="card-header">
            <div class="flex justify-content-between align-items-center">
                <div class="header-title">معلومات الاعلان</div>
                <a href="{{ route('admin.announcements.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
                @include('admin.partials.lang-select', [
                    'currentLang' => $currentLang,
                    'langs' => $langs,
                ])

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $announcementTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الوصف المختصر</div>
                <div class="show-field-value">{{ $announcementTrans->excerpt ?? 'لا يوجد' }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{!! $announcementTrans->body ?? 'لا يوجد' !!}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصورة</div>
                <div class="show-field-value">
                    @if (!is_null($announcement->image))
                        <img src="{{ asset('storage/'.$announcement->image) }}" alt="">
                    @else
                        لا يوجد صورة
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الملف</div>
                <div class="show-field-value">
                    @if (!is_null($announcement->file))
                        <a href="{{ asset('storage/'.$announcement->file) }}" target="_blank">عرض الملف</a>
                    @else
                        لا يوجد ملف
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">القسم</div>
                <div class="show-field-value">
                    {{ $announcement->category?->translate($currentLang, true)->title ?? 'لا يوجد' }}
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">رابط التقديم</div>
                <div class="show-field-value">
                    {{ $announcement->apply_link ?? 'لا يوجد' }}
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">
                    {{ Carbon\Carbon::parse($announcement->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">
                    {{ Carbon\Carbon::parse($announcement->updated_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}
                </div>
            </div>

            @can('edit-ad')
                <a href="{{ route('admin.announcements.edit', [$announcement, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection