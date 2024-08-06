@extends('admin.layout.app', [
    'title' => $document->title . ' | تعديل'
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
                <div class="card-title">تعديل المستند</div>
                <a href="{{ url()->previous() }}" class="ms-auto">عودة</a>
                <button class="btn btn-fill btn-primary" form="edit">
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
            <form action="{{ route('admin.documents.update', $document) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'الإسم',
                        'required' => true,
                        'value' => old('title') ?? $document->translate($currentLang)->title ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'slug',
                        'required' => true,
                        'value' => old('slug') ?? $document->slug
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'get_from_link',
                        'id' => 'get_from_link',
                        'label' =>  'استخدام رابط خارجي',
                        'checked' => old('get_from_link') ?? $document->get_from_link
                    ])
                </div>

                <div class="file-upload">
                    <div class="form-group">
                        <label for="file">رفع ملف</label>
                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept="application/pdf">
                        @if ($document->path)
                            <a class="uploaded-file-link" href="{{ asset('storage/'.$document->path) }}" target="_blank">الملف</a>
                        @endif

                        @error('file')
                            <div class="input-invalid">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @include('admin.partials.toggle', [
                        'name' => 'img_from_pdf',
                        'id' => 'img_from_pdf',
                        'label' =>  'جلب الصورة من الـ PDF',
                        'checked' => false
                    ])
                </div>

                <div class="external-link">
                    @include('admin.partials.text-input', [
                        'type' => 'url',
                        'name' => 'link',
                        'id' => 'link',
                        'label' => 'رابط الملف',
                        'placeholder' => 'رابط الملف',
                        'required' => false,
                        'value' => old('link') ?? $document->link
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'src' => asset('storage/'.$document->image)
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt') ?? $document->translate($currentLang)->excerpt ?? '',
                    'placeholder' => 'الوصف المختصر للخبر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'document-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body') ?? $document->translate($currentLang)->body ?? '',
                    'placeholder' => 'اضف المحتوى'
                ])

                <div class="form-group">
                    <label for="categories">القسم</label>
                    <select name="category_id" id="categories" class="form-control">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected($cat->id == $document->document_category_id)>
                                {{ $cat->translate($currentLang, true)?->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $document->status
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'featured',
                        'id' => 'featured',
                        'label' => 'مميز',
                        'checked' => old('featured') ?? $document->featured
                    ])
                </div>

                <button class="btn btn-fill btn-primary">
                    حفظ
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
        .file-upload {
            margin-bottom: 10px;
        }
        label[for="get_from_link"] {
            margin-bottom: 0;
        }
        .form-group:has(label[for="get_from_link"]) {
            margin-bottom: 10px;
        }
        .uploaded-file-link {
            font-size: 0.875rem;
            text-decoration: underline;
            color: blue;
            margin-top: 3px;
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
        const getImgFromPDF = document.getElementById('img_from_pdf');
        const PDFImage = document.getElementById('image');

        function toggleFileLink() {
            if (linkFileToggle.checked) {
                externalLink.style.display = 'block';
                fileUpload.style.display = 'none';
                externalLink.querySelector('input[name="link"]').required =  true;

                getImgFromPDF.checked = false;
                PDFImage.removeAttribute('disabled');
            } else {
                externalLink.style.display = 'none';
                fileUpload.style.display = 'block';
                externalLink.querySelector('input[name="link"]').required =  false;
            }
        }

        document.addEventListener('DOMContentLoaded', toggleFileLink);
        linkFileToggle.addEventListener('change', toggleFileLink);

        // Get image from PDF or not
        getImgFromPDF.addEventListener('change', function() {
            if (this.checked) {
                PDFImage.setAttribute('disabled', true);
            } else {
                PDFImage.removeAttribute('disabled');
            }
        });
    </script>
@endpush
