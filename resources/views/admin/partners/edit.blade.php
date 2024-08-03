@extends('admin.layout.app', [
    'title' => $partner->translate($currentLang, true)->name ?? '' . ' | تعديل'
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
                <div class="card-title">تعديل الشريك</div>
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
            <form action="{{ route('admin.partners.update', $partner) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'value' => old('name') ?? $partner->translate($currentLang)->name ?? ''
                    ])

                    <div class="form-group">
                        <label for="order">الترتيب</label>
                        <input type="number" name="order" id="order" class="form-control" min="0" step="1" value="{{ old('order') ?? $partner->order }}" placeholder="الترتيب">
                    </div>
                </div>

                @include('admin.partials.image-input', [
                    'id' => 'image',
                    'name' => 'image',
                    'label' => 'اختر صورة',
                    'required' => false,
                    'src' => asset('storage/'.$partner->image),
                ])

                @include('admin.partials.text-input', [
                    'type' => 'url',
                    'name' => 'link',
                    'id' => 'link',
                    'label' => 'الرابط',
                    'placeholder' => 'الرابط',
                    'required' => false,
                    'value' => old('link') ?? $partner->link
                ])

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $partner->status
                    ])
                </div>

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>
@endsection