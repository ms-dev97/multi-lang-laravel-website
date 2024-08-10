@extends('admin.layout.app', [
    'title' => $page->translate($currentLang, true)->name ?? '' . ' | تعديل'
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
                <div class="card-title">تعديل الصفحة</div>
                <a href="{{ route('admin.pages.index') }}" class="ms-auto">عودة</a>
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
            <form action="{{ route('admin.pages.update', $page) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'name' => 'name',
                        'id' => 'name',
                        'label' => 'الاسم',
                        'placeholder' => 'الاسم',
                        'required' => true,
                        'value' => old('name') ?? $page->translate($currentLang)->name ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'اسم الرابط',
                        'required' => true,
                        'value' => old('slug') ?? $page->slug
                    ])
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'src' => asset('storage/'.$page->image),
                ])

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt') ?? $page->translate($currentLang)->excerpt ?? '',
                    'placeholder' => 'ادخل الوصف المختصر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'page-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body') ?? $page->translate($currentLang)->body ?? '',
                    'placeholder' => 'اضف المحتوى'
                ])

                @role('super-admin')
                    <div class="input-half">
                        <div class="form-group">
                            @include('admin.partials.toggle', [
                                'name' => 'has_custom_view',
                                'id' => 'has_custom_view',
                                'label' => 'فيو مخصص',
                                'checked' => old('has_custom_view') ? true : ($page->has_custom_view ? true : false)
                            ])
                        </div>

                        @include('admin.partials.text-input', [
                            'type' => 'text',
                            'name' => 'view_name',
                            'id' => 'view_name',
                            'label' => 'اسم الفيو',
                            'placeholder' => 'اسم الفيو',
                            'required' => false,
                            'value' => old('view_name') ?? $page->view_name
                        ])
                    </div>
                @endrole

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $page->status
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