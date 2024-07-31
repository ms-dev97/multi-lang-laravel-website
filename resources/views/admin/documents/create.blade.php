@extends('admin.layout.app', [
    'title' => 'إضافة وثيقة | لوحة التحكم'
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
                <div class="card-title">إضافة وثيقة</div>
                <a href="{{ route('admin.documents.index') }}" class="ms-auto">عودة</a>
                <button class="btn btn-fill btn-primary" form="create">
                    إضافة
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
            <form action="{{ route('admin.documents.store') }}" method="POST" id="create" class="main-form" enctype="multipart/form-data">
                @csrf
                <select class="lang-select form-control" name="lang" id="lang">
                    @foreach ($langs as $lang)
                        <option value="{{ $lang }}">
                            {{ $lang }}
                        </option>
                    @endforeach
                </select>

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'title',
                        'id' => 'title',
                        'label' => 'الإسم',
                        'placeholder' => 'الاسم',
                        'required' => true,
                        'value' => old('title')
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'slug',
                        'placeholder' => 'slug',
                        'required' => true,
                        'value' => old('slug')
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'get_from_link',
                        'id' => 'get_from_link',
                        'label' =>  'استخدام رابط خارجي',
                        'checked' => old('get_from_link') == 'on' ? true : false
                    ])
                </div>

                <div class="form-group file-upload">
                    <label for="file">رفع ملف</label>
                    <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept="application/pdf">
                    @error('file')
                        <div class="input-invalid">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="external-link">
                    @include('admin.partials.text-input', [
                        'type' => 'url',
                        'name' => 'link',
                        'id' => 'link',
                        'label' => 'رابط الملف',
                        'placeholder' => 'رابط الملف',
                        'required' => false,
                        'value' => old('link')
                    ])
                </div>

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt'),
                    'placeholder' => 'ادخل الوصف المختصر للخبر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'document-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body'),
                    'placeholder' => 'اضف المحتوى'
                ])

                <div class="form-group">
                    <label for="categories">القسم</label>
                    <select name="category_id" id="categories" class="form-control">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->translate($currentLang, true)?->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => true
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'featured',
                        'id' => 'featured',
                        'label' => 'مميز',
                        'checked' => false
                    ])
                </div>

                <button class="btn btn-fill btn-primary">
                    إضافة
                </button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .external-link {
            display: none;
        }
    </style>
    <link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    {{-- jQuery required for select2 --}}
    <script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>

    @include('admin.partials.scripts.select2', [
        'selector' => '#categories',
        'placeholder' => 'اختر قسم',
    ])

    @include('admin.partials.scripts.rich-editor', ['direction' => 'rtl'])

    <script>
        // toggle between file upload and external link
        const linkFileToggle = document.getElementById('get_from_link');
        const fileUpload = document.querySelector('.file-upload');
        const externalLink = document.querySelector('.external-link');

        function toggleFileLink() {
            if (linkFileToggle.checked) {
                externalLink.style.display = 'block';
                fileUpload.style.display = 'none';
                externalLink.querySelector('input[name="link"]').required =  true;
                fileUpload.querySelector('input[name="file"]').required =  false;
            } else {
                externalLink.style.display = 'none';
                fileUpload.style.display = 'block';
                externalLink.querySelector('input[name="link"]').required =  false;
                fileUpload.querySelector('input[name="file"]').required =  true;
            }
        }

        document.addEventListener('DOMContentLoaded', toggleFileLink);
        linkFileToggle.addEventListener('change', toggleFileLink);
    </script>
@endpush