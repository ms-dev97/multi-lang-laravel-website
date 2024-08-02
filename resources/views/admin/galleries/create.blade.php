@extends('admin.layout.app', [
    'title' => 'إضافة معرض صور | لوحة التحكم'
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
                <div class="card-title">إضافة معرض صور</div>
                <a href="{{ route('admin.galleries.index') }}" class="ms-auto">عودة</a>
                <button type="submit" class="btn btn-fill btn-primary" form="create">
                    حفظ معرض صور
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
            <form action="{{ route('admin.galleries.store') }}" method="POST" id="create" class="main-form">
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
                        'label' => 'الاسم',
                        'placeholder' => 'الاسم',
                        'required' => true,
                        'value' => old('title')
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'اسم الرابط',
                        'placeholder' => 'example.com/galleries/gallery-name',
                        'required' => true,
                        'value' => old('slug')
                    ])
                </div>

                @include('admin.partials.textarea', [
                    'id' => 'excerpt',
                    'name' => 'excerpt',
                    'label' => 'الوصف المختصر',
                    'required' => false,
                    'value' => old('excerpt'),
                    'placeholder' => 'ادخل الوصف المختصر'
                ])

                @include('admin.partials.rich-textarea', [
                    'id' => 'gallery-body',
                    'name' => 'body',
                    'label' => 'المحتوى',
                    'required' => false,
                    'value' => old('body'),
                    'placeholder' => 'اضف المحتوى'
                ])

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
                    حفظ المعرض
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.partials.scripts.rich-editor', ['direction' => 'rtl'])
@endpush