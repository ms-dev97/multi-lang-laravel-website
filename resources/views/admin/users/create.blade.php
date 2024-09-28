@extends('admin.layout.app', [
    'title' => 'إضافة مستخدم | لوحة التحكم'
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
                <h1 class="card-title">إضافة مستخدم</h1>
                <a href="{{ route('admin.users.index') }}" class="ms-auto">عودة</a>
                <button class="btn btn-fill btn-primary" form="create">
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
            <form action="{{ route('admin.users.store') }}" method="POST" id="create" class="main-form">
                @csrf

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'name',
                        'id' => 'name',
                        'label' => 'الإسم',
                        'placeholder' => 'الإسم',
                        'required' => true,
                        'value' => old('name')
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'email',
                        'name' => 'email',
                        'id' => 'email',
                        'label' => 'البريد الالكتروني',
                        'placeholder' => 'البريد الالكتروني',
                        'required' => true,
                        'value' => old('email')
                    ])
                </div>

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'password',
                        'name' => 'password',
                        'id' => 'password',
                        'label' => 'كلمة المرور',
                        'placeholder' => 'كلمة المرور',
                        'required' => true,    
                    ])

                    <div class="form-group">
                        <label for="roles">الادوار</label>
                        <select name="roles[]" id="roles" multiple>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>

                        @error('roles')
                            <div class="input-invalid">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button class="btn btn-fill btn-primary">
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
    @include('admin.partials.scripts.select2', [
        'selector' => '#roles',
        'placeholder' => 'اختر الادوار',
    ])
@endpush