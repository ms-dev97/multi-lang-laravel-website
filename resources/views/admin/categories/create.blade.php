@extends('admin.layout.app', [
    'title' => 'إضافة قسم | لوحة التحكم'
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
                <div class="card-title">إضافة قسم</div>
                <a href="{{ route('admin.categories.index') }}" class="ms-auto">عودة</a>
                <button class="btn btn-fill btn-primary" form="create">
                    إضافة
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" id="create" class="main-form">
                @csrf
                <select class="lang-select form-control" name="lang" id="lang">
                    @foreach ($langs as $lang)
                        <option value="{{ $lang }}"  data-url="{{ request()->fullUrlWithQuery(['lang' => $lang]) }}">
                            {{ $lang }}
                        </option>
                    @endforeach
                </select>

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'title',
                        'id' => 'title',
                        'label' => 'الإسم',
                        'required' => true,
                        'value' => old('title')
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'slug',
                        'id' => 'slug',
                        'label' => 'slug',
                        'required' => false,
                        'value' => old('slug')
                    ])
                </div>

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

                <button class="btn btn-fill btn-primary">
                    إضافة
                </button>
            </form>
        </div>
    </div>
@endsection