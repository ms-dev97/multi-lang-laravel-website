@extends('admin.layout.app', [
    'title' => $permission->name . ' | تعديل'
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
                <div class="card-title">تعديل الاذن</div>
                <a href="{{ route('admin.permissions.index') }}" class="ms-auto">عودة</a>
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
            <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" id="edit" class="main-form">
                @csrf
                @method('put')

                <div class="flex g-1rem flex-wrap">
                    <div class="flex-1">
                        @include('admin.partials.text-input', [
                            'type' => 'text',
                            'name' => 'name',
                            'id' => 'name',
                            'label' => 'الاسم',
                            'placeholder' => 'الاسم',
                            'required' => true,
                            'value' => old('name') ?? $permission->name
                        ])
                    </div>

                    <div class="flex-1">
                        @include('admin.partials.text-input', [
                            'type' => 'text',
                            'name' => 'display_name',
                            'id' => 'display_name',
                            'label' => 'اسم العرض',
                            'placeholder' => 'اسم العرض',
                            'required' => true,
                            'value' => old('display_name') ?? $permission->display_name
                        ])
                    </div>

                    <div class="flex-1">
                        @include('admin.partials.text-input', [
                            'type' => 'text',
                            'name' => 'table_name',
                            'id' => 'table_name',
                            'label' => 'اسم الجدول',
                            'placeholder' => 'اسم الجدول',
                            'required' => false,
                            'value' => old('table_name') ?? $permission->table_name
                        ])
                    </div>
                </div>

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>
@endsection