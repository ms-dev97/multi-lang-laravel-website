@extends('admin.layout.app', [
    'title' => 'إضافة مشروع | لوحة التحكم'
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
                <div class="card-title">إضافة مشروع</div>
                <a href="{{ route('admin.projects.index') }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="create">
                    حفظ المشروع
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.projects.store') }}" method="POST" id="create" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'اسم المشروع',
                        'placeholder' => 'اسم المشروع',
                        'required' => true,
                        'value' => old('title')
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'example.com/projects/project-name',
                        'required' => true,
                        'value' => old('slug')
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'value' => old('image')
                ])

                @include('admin.partials.image-input', [
                    'id' => 'cover',
                    'name' => 'cover',
                    'label' => 'صورة البانر',
                    'required' => false,
                    'value' => old('cover')
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt'),
                    'placeholder' => 'ادخل الوصف المختصر للبرنامج'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'project-body',
                    'name' => 'body',
                    'label' => 'محتوى المشروع',
                    'required' => false,
                    'value' => old('body'),
                    'placeholder' => 'اضف محتوى المشروع'
                ])

                <div class="form-group">
                    <label for="program_id">البرنامج</label>
                    <select name="program_id" id="program_id" class="form-control" multiple>
                        @foreach ($programs as $program)
                            <option value="{{ $programs->id }}">{{ $program->translate($currentLang)?->title }}</option>
                        @endforeach
                    </select>
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
                    حفظ البرنامج
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
        'selector' => '#program_id',
        'placeholder' => 'اختر البرنامج',
    ])
@endpush