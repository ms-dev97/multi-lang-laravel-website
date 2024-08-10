@extends('admin.layout.app', [
    'title' => $role->name . ' | لوحة التحكم'
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
            <div class="flex justify-content-between align-items-center">
                <div class="card-title">معلومات الدور</div>
                <a href="{{ route('admin.roles.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $role->name }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">اسم العرض</div>
                <div class="show-field-value">{{ $role->display_name }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصلاحيات</div>
                <ul class="permissions">
                    @foreach ($rolePermissions as $permission)
                        <li class="permission">
                            {{ $permission->display_name }}
                        </li>
                    @endforeach
                </ul>
            </div>

            @can('edit-role')
                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .permission {
            margin-bottom: 5px;
        }
    </style>
@endpush