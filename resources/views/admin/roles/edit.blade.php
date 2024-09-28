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

        @if ($errors->any())
            <ul class="form-errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

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
                    @foreach ($permissions as $table => $permission)
                        <div class="permission-group">

                        
                            @if ($table)
                                <div class="table-name">{{ $table }}</div>
                            @endif
                            
                            @foreach ($permission as $perm)
                                <div class="permission flex align-items-center g-0.5rem">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        id="permission-{{ $perm->id }}"
                                        value="{{ $perm->id }}"
                                        @checked(in_array($perm->id, $rolePermissionsIds))
                                    >
                                    <label for="permission-{{ $perm->id }}">
                                        {{ $perm->display_name }}
                                    </label>
                                </div>
                            @endforeach
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
        .permission-group {
            margin-bottom: 1rem;
        }
        .permission-group .table-name {
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 1.125rem;
        }
        .permissions {
            margin-bottom: 1rem;
        }
        .permission {
            margin-bottom: 5px;
        }
    </style>
@endpush