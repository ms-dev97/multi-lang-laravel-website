@extends('admin.layout.app', [
    'title' => $video->translate($currentLang, true)->name ?? '' . ' | تعديل'
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
                <div class="card-title">تعديل فيديو</div>
                <a href="{{ url()->previous() }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="edit">
                    حفظ
                </button>
            </div>
        </div>

        @if ($errors->any())
            <ul class="form-errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="card-body">
            <form action="{{ route('admin.videos.update', $video) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'العنوان',
                        'placeholder' => 'العنوان',
                        'required' => true,
                        'value' => old('title') ?? $video->translate($currentLang)->title ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'اسم الرابط',
                        'required' => true,
                        'value' => old('slug') ?? $video->slug
                    ])
                </div>

                @include('admin.partials.text-input', [
                    'type' => 'url',
                    'name' => 'link',
                    'id' => 'link',
                    'label' => 'رابط الفيديو من اليوتيوب',
                    'placeholder' => 'رابط الفيديو من اليوتيوب',
                    'required' => true,
                    'value' => old('link') ?? $video->link
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt') ?? $video->translate($currentLang)->excerpt ?? '',
                    'placeholder' => 'ادخل الوصف المختصر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'video-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body') ?? $video->translate($currentLang)->body ?? '',
                    'placeholder' => 'اضف محتوى'
                ])

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $video->status
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'featured',
                        'id' => 'featured',
                        'label' => 'مميز',
                        'checked' => old('featured') ?? $video->featured
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
    @include('admin.partials.scripts.rich-editor', ['direction' => 'rtl'])
@endpush