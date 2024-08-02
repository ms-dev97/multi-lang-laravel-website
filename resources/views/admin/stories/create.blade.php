@extends('admin.layout.app', [
    'title' => 'إضافة قصة نجاح | لوحة التحكم'
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
                <div class="card-title">إضافة قصة نجاح</div>
                <a href="{{ route('admin.stories.index') }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="create">
                    حفظ قصة النجاح
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.stories.store') }}" method="POST" id="create" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'العنوان',
                        'placeholder' => 'العنوان',
                        'required' => true,
                        'value' => old('title')
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'example.com/stories/story-name',
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

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt'),
                    'placeholder' => 'ادخل الوصف المختصر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'story-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body'),
                    'placeholder' => 'اضف المحتوى'
                ])

                <div class="input-half">
                    <div class="form-group">
                        <label for="program_id">البرنامج</label>
                        <select name="program_id" id="program_id" class="form-control">
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->translate($currentLang, true)?->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="project_id">المشروع</label>
                        <select name="project_id" id="project_id" class="form-control">
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->translate($currentLang, true)?->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-half">
                    <div class="form-group">
                        <label for="type">النوع</label>
                        <select name="type" id="type">
                            <option value="1">قصة مقروءة</option>
                            <option value="2">قصة فيديو</option>
                        </select>
                    </div>

                    @include('admin.partials.text-input', [
                        'type' => 'url',
                        'name' => 'video_link',
                        'id' => 'video_link',
                        'label' => 'رابط الفيديو',
                        'placeholder' => 'رابط الفيديو',
                        'required' => false,
                        'value' => old('video_link')
                    ])
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
                    حفظ قصة النجاح
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

    @include('admin.partials.scripts.select2', [
        'selector' => '#project_id',
        'placeholder' => 'اختر المشروع',
    ])
@endpush