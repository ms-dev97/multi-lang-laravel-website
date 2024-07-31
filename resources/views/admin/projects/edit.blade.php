@extends('admin.layout.app', [
    'title' => $project->title . ' | تعديل'
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
                <div class="card-title">تعديل المشروع</div>
                <a href="{{ url()->previous() }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="edit">
                    حفظ
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.projects.update', $project) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'اسم المشروع',
                        'placeholder' => 'اسم المشروع',
                        'required' => true,
                        'value' => old('title') ?? $project->translate($currentLang)->title ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'example.com/projects/project-name',
                        'required' => true,
                        'value' => old('slug') ?? $project->slug
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'value' => old('image'),
                    'src' => asset('storage/'.$project->image),
                ])

                @include('admin.partials.image-input', [
                    'id' => 'cover',
                    'name' => 'cover',
                    'label' => 'صورة البانر',
                    'required' => false,
                    'value' => old('cover'),
                    'src' => asset('storage/'.$project->cover),
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt') ?? $project->translate($currentLang)->excerpt ?? '',
                    'placeholder' => 'ادخل الوصف المختصر للمشروع'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'project-body',
                    'name' => 'body',
                    'label' => 'محتوى المشروع',
                    'required' => false,
                    'value' => old('body') ?? $project->translate($currentLang)->body ?? '',
                    'placeholder' => 'محتوى المشروع'
                ])

                <div class="form-group">
                    <label for="program_id">المشروع</label>
                    <select name="program_id" id="program_id" class="form-control" multiple>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}" @selected($program->id == $project->program_id)>
                                {{ $program->translate($currentLang)?->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @include('admin.partials.lfm-media-picker', [
                    'gallery_input' => old('gallery-input') ?? implode(',', $project->gallery ?? []),
                    'gallery_items' => $project->gallery,
                ])

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $project->status
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'featured',
                        'id' => 'featured',
                        'label' => 'مميز',
                        'checked' => old('featured') ?? $project->featured
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

    @include('admin.partials.scripts.rich-editor', ['direction' => $currentLang == 'ar' ? 'rtl' : 'ltr'])

    @include('admin.partials.scripts.select2', [
        'selector' => '#program_id',
        'placeholder' => 'اختر البرنامج',
    ])
@endpush