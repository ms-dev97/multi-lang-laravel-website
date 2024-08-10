@extends('admin.layout.app', [
    'title' => 'إضافة خبر | لوحة التحكم'
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
                <div class="card-title">إضافة خبر</div>
                <a href="{{ route('admin.news.index') }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="create">
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
            <form action="{{ route('admin.news.store') }}" method="POST" id="create" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'عنوان الخبر',
                        'placeholder' => 'عنوان الخبر',
                        'required' => true,
                        'value' => old('title')
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'اسم الرابط',
                        'required' => true,
                        'value' => old('slug')
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt'),
                    'placeholder' => 'ادخل الوصف المختصر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'news-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body'),
                    'placeholder' => 'اضف المحتوى'
                ])

                <div class="flex g-1rem flex-wrap">
                    <div class="form-group flex-1">
                        <label for="categories">الأقسام</label>
                        <select name="categories[]" id="categories" class="form-control" multiple>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->translate($currentLang, true)?->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group flex-1">
                        <label for="programs">البرامج</label>
                        <select name="programs[]" id="programs" class="form-control" multiple>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->translate($currentLang, true)?->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group flex-1">
                        <label for="projects">المشاريع</label>
                        <select name="projects[]" id="projects" class="form-control" multiple>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->translate($currentLang, true)?->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Media picker --}}
                @include('admin.partials.lfm-media-picker')

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

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
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

    @include('admin.partials.scripts.rich-editor', ['direction' => 'rtl'])

    @include('admin.partials.scripts.select2', [
        'selector' => '#categories',
        'placeholder' => 'الاقسام',
    ])

    @include('admin.partials.scripts.select2', [
        'selector' => '#programs',
        'placeholder' => 'البرامج',
    ])

    @include('admin.partials.scripts.select2', [
        'selector' => '#projects',
        'placeholder' => 'المشاريع',
    ])
@endpush