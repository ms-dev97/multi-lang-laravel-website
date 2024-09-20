@extends('admin.layout.app', [
    'title' => $news->translate($currentLang, true)->title . ' | تعديل'
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
                <div class="card-title">تعديل الخبر</div>
                <a href="{{ route('admin.news.index') }}" class="ms-auto">عودة</a>
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
            <form action="{{ route('admin.news.update', $news) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'عنوان الخبر',
                        'placeholder' => 'عنوان الخبر',
                        'required' => true,
                        'value' => old('title') ?? $news->translate($currentLang)->title ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'اسم الرابط',
                        'required' => true,
                        'value' => old('slug') ?? $news->slug
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'src' => asset('storage/'.$news->image),
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt') ?? $news->translate($currentLang)->excerpt ?? '',
                    'placeholder' => 'ادخل الوصف المختصر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'news-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body') ?? $news->translate($currentLang)->body ?? '',
                    'placeholder' => 'اضف المحتوى'
                ])

                <div class="flex g-1rem flex-wrap">
                    <div class="form-group flex-1">
                        <label for="categories">الأقسام</label>
                        <select name="categories[]" id="categories" class="form-control" multiple>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(in_array($cat->id, $newsCats))>
                                    {{ $cat->translate($currentLang, true)?->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group flex-1">
                        <label for="programs">البرامج</label>
                        <select name="programs[]" id="programs" class="form-control" multiple>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" @selected(in_array($program->id, $newsPrograms))>
                                    {{ $program->translate($currentLang, true)?->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group flex-1">
                        <label for="projects">المشاريع</label>
                        <select name="projects[]" id="projects" class="form-control" multiple>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected(in_array($project->id, $newsProjects))>
                                    {{ $project->translate($currentLang, true)?->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @include('admin.partials.lfm-media-picker', [
                    'gallery_input' => old('gallery-input') ?? implode(',', $news->gallery ?? []),
                    'gallery_items' => old('gallery-input') ? explode(',', old('gallery-input')) : $news->gallery,
                ])

                @include('admin.partials.text-input', [
                    'type' => 'text',
                    'name' => 'created_at',
                    'id' => 'created_at',
                    'label' => 'تعديل تاريخ النشر',
                    'placeholder' => 'تعديل تاريخ النشر',
                    'required' => false,
                    'value' => old('created_at') ?? $news->created_at
                ])

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $news->status
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'featured',
                        'id' => 'featured',
                        'label' => 'مميز',
                        'checked' => old('featured') ?? $news->featured
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
    @include('admin.partials.scripts.rich-editor', ['direction' => $currentLang == 'ar' ? 'rtl' : 'ltr'])

    {{-- jQuery required for select2 --}}
    <script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#created_at", {
            'enableTime': true,
            'enableSeconds': true,
            'dateFormat': 'Y-m-d H:i:s'
        });
    </script>

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