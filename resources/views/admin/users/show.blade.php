@extends('admin.layout.app', [
    'title' => $user->name . ' | لوحة التحكم'
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
                <div class="header-title">معلومات المستخدم</div>
                <a href="{{ route('admin.users.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $user->name }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">البريد الالكتروني</div>
                <div class="show-field-value">{{ $user->email }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الانضمام</div>
                <div class="show-field-value">
                    {{ \Carbon\Carbon::parse($user->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الأدوار</div>
                <div class="show-field-value">{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</div>
            </div>

            @can('edit-user')
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection