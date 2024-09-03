@extends('admin.layout.app', [
    'title' => 'الأعدادات | لوحة التحكم'
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
        <div class="card-header flex justify-content-between">
            <h1 class="card-title">الأعدادات</h1>
        </div>

        <div class="card-body">
            <div class="setting-tabs">
                @foreach ($groupedSettings as $group => $settings)
                    <div @class(['tab', 'active' => $loop->iteration == 1]) data-group="{{ $group }}">
                        {{ $group }}
                    </div>
                @endforeach
            </div>

            <form class="settings-form" method="post" action="{{ route('admin.settings.updateAll') }}" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="tab-content-wrapper">
                    @foreach ($groupedSettings as $group => $settings)
                        <div @class(['tab-content', 'active' => $loop->iteration == 1]) data-group="{{ $group }}">
                            @foreach ($settings as $setting)
                                @if ($setting->type == 'image')
                                    @include('admin.partials.image-input', [
                                        'id' => $setting->key,
                                        'name' => $setting->key,
                                        'label' => $setting->display_name,
                                        'required' => false,
                                        'value' => old($setting->key) ?? '',
                                        'src' => asset('storage/'.$setting->value),
                                    ])
                                @else
                                    @include('admin.partials.text-input',[
                                        'id' => $setting->key,
                                        'name' => $setting->key,
                                        'label' => $setting->display_name,
                                        'type' => $setting->type,
                                        'value' => old($setting->key) ?? $setting->value ?? '',
                                        'placeholder' => $setting->display_name,
                                        'required' => false,
                                    ])
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-fill btn-primary">
                    حفظ
                </button>
            </form>
        </div>
    </div>

    @role('super-admin')
        <div class="card create-setting-card">
            <div class="card-header">
                <h2 class="card-title">اضافة إعداد جديد</h2>
            </div>

            <div class="card-body">
                <form class="create-setting-form" action="{{ route('admin.settings.store') }}" method="post">
                    @csrf

                    @include('admin.partials.text-input', [
                        'id' => 'display_name',
                        'name' => 'display_name',
                        'value' => old('display_name') ?? '',
                        'label' => 'الاسم',
                        'type' => 'text',
                        'placeholder' => 'الاسم',
                        'required' => true,
                    ])

                    @include('admin.partials.text-input', [
                        'id' => 'key',
                        'name' => 'key',
                        'value' => old('key') ?? '',
                        'label' => 'المفتاح',
                        'type' => 'text',
                        'placeholder' => 'المفتاح',
                        'required' => true,
                    ])

                    <div class="form-group">
                        <label for="type">نوع الحقل</label>
                        <select name="type" id="type" required>
                            <option value="text" selected>مربع نص</option>
                            <option value="image">صورة</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="group">المجموعة</label>
                        <select name="group" id="group" required>
                            <option value="admin" selected>الادمن</option>
                            <option value="info">معلومات</option>
                            <option value="backgrounds">الخلفيات</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-fill btn-primary">
                        حفظ
                    </button>
                </form>
            </div>
        </div>
    @endrole
@endsection

@push('styles')
    <style>
        .create-setting-card {
            margin-block: 2rem;
        }
        @media screen and (min-width: 48em) {
            .create-setting-form {
                display: flex;
                justify-content: space-between;
                align-items: end;
                gap: 1rem;
                flex-wrap: wrap;
            }
            .create-setting-form > * {
                flex: 1;
                margin-bottom: 0;
            }
            .create-setting-form .btn {
                flex: 0;
                height: 35px;
            }
        }
        .setting-tabs {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        .setting-tabs .tab {
            background-color: var(--neutral-100);
            padding: 5px 13px;
            cursor: pointer;
            transition: color 0.2s, background-color 0.2s;
        }
        .setting-tabs .tab.active {
            background-color: var(--primary);
            color: #fff;
            border-inline-end-color: transparent;
        }
        .setting-tabs .tab:not(:last-of-type) {
            border-inline-end: 1px solid var(--neutral-200);
        }
        .tab-content-wrapper {
            display: grid;
            grid-template-columns: 1fr;
        }
        .tab-content {
            grid-column: 1 / -1;
            grid-row: 1 / -1;
            background-color: var(--card-bg);
            display: none;
            opacity: 0;
            transition: opacity 0.2s, display 0.2s;
            transition-behavior: allow-discrete;
        }
        .tab-content.active {
            display: block;
            opacity: 1;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const tabs = document.querySelectorAll('.tab');
        const tabsContent = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('pointerdown', function() {
                const tabGroup = this.dataset.group;
                
                tabs.forEach(tab => tab.classList.remove('active'));
                this.classList.add('active');

                tabsContent.forEach(item => {
                    const tabContentGroup = item.dataset.group;
                    
                    if (tabContentGroup == tabGroup) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            });
        });
    </script>
@endpush