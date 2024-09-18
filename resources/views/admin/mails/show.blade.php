@extends('admin.layout.app', [
    'title' => $mail->subject . ' | لوحة التحكم'
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
                <div class="card-title">معلومات الرسالة</div>
                <a href="{{ route('admin.mails.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            <div class="show-field">
                <div class="show-field-name">الموضوع</div>
                <div class="show-field-value">{{ $mail->subject }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الاسم</div>
                <div class="show-field-value">{{ $mail->name }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">رقم الهاتف</div>
                <div class="show-field-value">{{ $mail->phone_number }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الحالة</div>
                <div class="show-field-value">{{ $mail->status }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الرسالة</div>
                <div class="show-field-value">{!! $mail->message !!}</div>
            </div>
        </div>
    </div>
@endsection