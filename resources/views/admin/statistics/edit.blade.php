@extends('admin.layout.app', [
    'title' => $statistic->translate($currentLang, true)->name ?? '' . ' | تعديل'
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
                <div class="card-title">تعديل الاحصائيات</div>
                <a href="{{ route('admin.statistics.index') }}" class="ms-auto">عودة</a>
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
            <form action="{{ route('admin.statistics.update', $statistic) }}" method="POST" id="edit" class="main-form" enctype="multipart/form-data">
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
                        'value' => old('name') ?? $statistic->translate($currentLang)->name ?? ''
                    ])

                    <div class="form-group">
                        <label for="order">الترتيب</label>
                        <input type="number" name="order" id="order" class="form-control" min="0" step="1" value="{{ old('order') ?? $statistic->order }}" placeholder="الترتيب">
                    </div>
                </div>

                <div class="input-half">
                    @include('admin.partials.image-input', [
                        'id' => 'image',
                        'name' => 'image',
                        'label' => 'اختر صورة',
                        'required' => false,
                        'src' => asset('storage/'.$statistic->icon),
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'number',
                        'name' => 'number',
                        'id' => 'number',
                        'label' => 'الرقم',
                        'placeholder' => 'الرقم',
                        'required' => true,
                        'value' => old('number') ?? $statistic->number
                    ])
                </div>

                <div class="form-group">
                    @include('admin.partials.toggle', [
                        'name' => 'status',
                        'id' => 'status',
                        'label' => 'الحالة',
                        'checked' => old('status') ?? $statistic->status
                    ])
                </div>

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>
@endsection