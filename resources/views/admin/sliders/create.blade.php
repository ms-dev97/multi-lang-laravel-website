@extends('admin.layout.app', [
    'title' => 'إضافة سلايد | لوحة التحكم'
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
                <div class="card-title">إضافة سلايد</div>
                <a href="{{ route('admin.sliders.index') }}" class="ms-auto">عودة</a>
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
            <form action="{{ route('admin.sliders.store') }}" method="POST" id="create" class="main-form" enctype="multipart/form-data">
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

                    <div class="form-group">
                        <label for="order">الترتيب</label>
                        <input type="number" name="order" id="order" class="form-control" min="0" value="{{ old('order', 0) }}" placeholder="الترتيب">
                    </div>
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => true,
                ])

                @include('admin.partials.text-input', [
                    'type' => 'url',
                    'name' => 'link',
                    'id' => 'link',
                    'label' => 'الرابط',
                    'placeholder' => 'الرابط',
                    'required' => false,
                    'value' => old('link')
                ])

                <div class="form-group">
                    <label for="slider_location">موقع السلايدر</label>
                    <select name="slider_location" id="slider_location" class="form-control">
                        <option value="1">الصفحة الرئيسية</option>
                    </select>
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => true
                    ])
                </div>

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>
@endsection