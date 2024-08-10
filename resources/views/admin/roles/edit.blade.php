@extends('admin.layout.app', [
    'title' => $role->name . ' | تعديل'
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
                <div class="card-title">تعديل الدور</div>
                <a href="{{ route('admin.roles.index') }}" class="ms-auto">عودة</a>
                <button class="btn btn-fill btn-primary" form="edit">
                    حفظ
                </button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST" id="edit" class="main-form">
                @csrf
                @method('put')

                <div class="input-half">
                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'name',
                        'id' => 'name',
                        'label' => 'الإسم',
                        'required' => true,
                        'value' => old('name') ?? $role->name ?? ''
                    ])

                    @include('admin.partials.text-input', [
                        'type' => 'text',
                        'name' => 'display_name',
                        'id' => 'display_name',
                        'label' => 'اسم العرض',
                        'required' => true,
                        'value' => old('display_name') ?? $role->display_name ?? ''
                    ])
                </div>

                <h2>الصلاحيات</h2>

                <div class="permissions">
                    @foreach ($permissions as $permission)
                        <div class="permission flex align-items-center g-0.5rem">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                id="permission-{{ $permission->id }}"
                                value="{{ $permission->id }}"
                                @checked(in_array($permission->id, $rolePermissionsIds))
                            >
                            <label for="permission-{{ $permission->id }}">
                                {{ $permission->display_name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .permissions {
            margin-bottom: 1rem;
        }
        .permission {
            margin-bottom: 5px;
        }
    </style>
@endpush