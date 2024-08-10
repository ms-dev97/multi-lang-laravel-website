@extends('admin.layout.app', [
    'title' => $program->translate($currentLang, true)->title ?? '' . ' | تعديل'
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
            <div class="flex justify-content-between align-items-center g-1rem">
                <div class="card-title">تعديل البرنامج</div>
                <a href="{{ route('admin.programs.index') }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="edit">
                    حفظ
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.programs.update', $program) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
                @csrf
                @method('put')

                @include('admin.partials.lang-select', [
                    'currentLang' => $currentLang,
                    'langs' => $langs,
                ])

                <input type="hidden" name="lang" value="{{ $currentLang }}">

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'title',
                        'id' => 'title',
                        'label' => 'اسم البرنامج',
                        'placeholder' => 'اسم البرنامج',
                        'required' => true,
                        'value' => old('title') ?? $program->translate($currentLang)->title ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'اسم الرابط',
                        'required' => true,
                        'value' => old('slug') ?? $program->slug
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'src' => asset('storage/'.$program->image),
                ])

                @include('admin.partials.image-input', [
                    'id' => 'icon',
                    'name' => 'icon',
                    'label' => 'الايقونة',
                    'required' => false,
                    'src' => asset('storage/'.$program->icon),
                ])

                @include('admin.partials.image-input', [
                    'id' => 'cover',
                    'name' => 'cover',
                    'label' => 'صورة البانر',
                    'required' => false,
                    'src' => asset('storage/'.$program->cover),
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt') ?? $program->translate($currentLang)->excerpt ?? '',
                    'placeholder' => 'ادخل الوصف المختصر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'program-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body') ?? $program->translate($currentLang)->body ?? '',
                    'placeholder' => 'اضف المحتوى'
                ])

                @include('admin.partials.lfm-media-picker', [
                    'gallery_input' => old('gallery-input') ?? implode(',', $program->gallery ?? []),
                    'gallery_items' => old('gallery-inout') ? explode(',', old('gallery-inout')) : $program->gallery,
                ])

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $program->status
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'featured',
                        'id' => 'featured',
                        'label' => 'مميز',
                        'checked' => old('featured') ?? $program->featured
                    ])
                </div>

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.partials.scripts.rich-editor', ['direction' => $currentLang == 'ar' ? 'rtl' : 'ltr'])
@endpush