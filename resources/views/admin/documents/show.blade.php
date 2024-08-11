@extends('admin.layout.app', [
    'title' => $documentTrans->title
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
                <div class="card-title">معلومات المستند</div>
                <a href="{{ route('admin.documents.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
                @include('admin.partials.lang-select', [
                    'currentLang' => $currentLang,
                    'langs' => $langs,
                ])

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $documentTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الوصف المختصر</div>
                <div class="show-field-value">{{ $documentTrans->excerpt ?? 'لا يوجد' }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{{ $documentTrans->body ?? 'لا يوجد' }}</div>
            </div>

            <hr>

            @if ($document->get_from_link)
                <div class="show-field">
                    <div class="show-field-name">رابط المستند</div>
                    <div class="show-field-value">{{ $document->link ?? 'لا يوجد' }}</div>
                </div>

                <hr>
            @else
                <div class="show-field">
                    <div class="show-field-name">المستند</div>
                    <div class="show-field-value">
                        <a href="{{ asset('storage/' . $document->file) }}">معاينة</a>
                    </div>
                </div>

                <hr>
            @endif

            <div class="show-field">
                <div class="show-field-name">القسم</div>
                <div class="show-field-value">{{ $document->category?->translate($currentLang, true)->title ?? 'لا يوجد' }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($document->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($document->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edit-document')
                <a href="{{ route('admin.documents.edit', [$document, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection