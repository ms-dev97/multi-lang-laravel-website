@extends('admin.layout.app', [
    'title' => 'إضافة اعلان | لوحة التحكم'
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
                <div class="card-title">إضافة اعلان</div>
                <a href="{{ route('admin.announcements.index') }}" class="ms-auto">عودة</a>
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
            <form action="{{ route('admin.announcements.store') }}" method="POST" id="create" class="main-form" enctype="multipart/form-data">
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
                </div>

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt'),
                    'placeholder' => 'ادخل الوصف المختصر للاعلان'
                ])

                <div class="input-half">
                    @include('admin.partials.image-input', [
                        'id' => 'image',
                        'name' => 'image',
                        'label' => 'اختر صورة',
                        'required' => false  
                    ])

                    <div class="form-group">
                        <label for="file">رفع ملف</label>
                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept="application/pdf">
                        @error('file')
                            <div class="input-invalid">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                @include('admin.partials.rich-textarea', [
                    'id' => 'ad-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body'),
                    'placeholder' => 'اضف المحتوى'
                ])

                <div class="input-half">
                    <div class="form-group flex-1">
                        <label for="ad_category_id">الأقسام</label>
                        <select name="ad_category_id" id="ad_category_id" class="form-control">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->translate($currentLang, true)?->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    @include('admin.partials.text-input', [
                        'id' => 'apply_link',
                        'name' => 'apply_link',
                        'label' => 'رابط التقديم',
                        'placeholder' => 'رابط التقديم',
                        'type' => 'url',
                        'required' => false,
                        'value' => old('apply_link')    
                    ])
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
    <link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    {{-- jQuery required for select2 --}}
    <script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>

    @include('admin.partials.scripts.select2', [
        'selector' => '#ad_category_id',
        'placeholder' => 'اختر قسم',
    ])

    @include('admin.partials.scripts.rich-editor', ['direction' => 'rtl'])
@endpush