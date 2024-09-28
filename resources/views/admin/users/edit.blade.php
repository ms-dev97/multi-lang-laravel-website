@extends('admin.layout.app', [
    'title' => $user->name . ' | تعديل'
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
                <div class="card-title">تعديل بيانات المستخدم</div>
                <a href="{{ route('admin.users.index') }}" class="ms-auto">عودة</a>
                <button class="btn btn-fill btn-primary" form="edit">
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
            <form action="{{ route('admin.users.update', $user) }}" method="POST" id="edit" class="main-form">
                @csrf
                @method('put')

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'name',
                        'id' => 'name',
                        'label' => 'الإسم',
                        'required' => true,
                        'value' => old('name') ?? $user->name ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'email',
                        'name' => 'email',
                        'id' => 'email',
                        'label' => 'البريد الالكتروني',
                        'placeholder' => 'البريد الالكتروني',
                        'required' => true,
                        'value' => old('email') ?? $user->email ?? ''
                    ])
                </div>

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'password',
                        'name' => 'password',
                        'id' => 'password',
                        'label' => 'كلمة المرور',
                        'placeholder' => 'كلمة المرور',
                        'required' => false,    
                    ])

                    <div class="form-group">
                        <label for="roles">الادوار</label>
                        <select name="roles[]" id="roles" multiple>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(in_array($role->name, $userRolesNames))>
                                    {{ $role->name }}
                                </option>
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
