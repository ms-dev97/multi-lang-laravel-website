@extends('admin.layout.app', [
    'title' => $slider->translate($currentLang, true)->title ?? '' . ' | تعديل'
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
                <div class="card-title">تعديل السلايد</div>
                <a href="{{ url()->previous() }}" class="ms-auto">عودة</a>
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
            <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'label' => 'العنوان',
                        'placeholder' => 'العنوان',
                        'required' => true,
                        'value' => old('title') ?? $slider->translate($currentLang)->title ?? ''
                    ])

                    <div class="form-group">
                        <label for="order">الترتيب</label>
                        <input type="number" name="order" id="order" class="form-control" min="0" value="{{ old('order') ?? $slider->order }}" placeholder="الترتيب">
                    </div>
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => true,
                    'src' => asset('storage/'.$slider->image),
                ])

                <div class="form-group">
                    <label for="slider_location">موقع السلايدر</label>
                    <select name="slider_location" id="slider_location" class="form-control">
                        <option value="1" @selected($slider->slider_location == 1)>
                            الصفحة الرئيسية
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $slider->status
                    ])
                </div>

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>
@endsection