@extends('admin.layout.app', [
    'title' => $news->title . ' | تعديل'
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
            <div class="alert warning">{{ session('warning') }}</div>
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
                <a href="{{ url()->previous() }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="edit">
                    حفظ
                </button>
            </div>
        </div>

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
                        'label' => 'slug',
                        'placeholder' => 'slug',
                        'required' => false,
                        'value' => old('slug') ?? $news->slug
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'value' => old('image'),
                    'src' => asset('storage/'.$news->image),
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt') ?? $news->translate($currentLang)->excerpt ?? '',
                    'placeholder' => 'ادخل الوصف المختصر للخبر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'news-body',
                    'name' => 'body',
                    'label' => 'محتوى الخبر',
                    'required' => false,
                    'value' => old('body') ?? $news->translate($currentLang)->body ?? '',
                    'placeholder' => 'اضف محتوى الخبر'
                ])

                <div class="flex g-1rem flex-wrap">
                    <div class="form-group flex-1">
                        <label for="categories">الأقسام</label>
                        <select name="categories[]" id="categories" class="form-control" multiple>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(in_array($cat->id, $newsCats))>
                                    {{ $cat->translate($currentLang)?->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group flex-1">
                        <label for="programs">البرامج</label>
                        <select name="programs[]" id="programs" class="form-control" multiple>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" @selected(in_array($program->id, $newsPrograms))>
                                    {{ $program->translate($currentLang)?->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @include('admin.partials.lfm-media-picker', [
                    'gallery_input' => old('gallery-input') ?? implode(',', $news->gallery ?? []),
                    'gallery_items' => $news->gallery,
                    'gallery' => $news->gallery
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
@endpush

@push('scripts')
    @include('admin.partials.scripts.rich-editor', ['direction' => $currentLang == 'ar' ? 'rtl' : 'ltr'])

    {{-- jQuery required for select2 --}}
    <script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>

    @include('admin.partials.scripts.select2', [
        'selector' => '#categories',
        'placeholder' => 'اختر قسم',
    ])

    @include('admin.partials.scripts.select2', [
        'selector' => '#programs',
        'placeholder' => 'اختر برنامج',
    ])
@endpush