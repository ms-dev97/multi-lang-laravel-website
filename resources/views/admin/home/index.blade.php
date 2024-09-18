@extends('admin.layout.app', [
    'title' => 'الرئيسية | لوحة التحكم'
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

    <div class="stats">
        @php
            $stats = [
                [
                    'name' => 'الأخبار',
                    'count' => $newsCount,
                    'addRoute' => route('admin.news.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2 4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4ZM4 5V19H20V5H4ZM6 7H12V13H6V7ZM8 9V11H10V9H8ZM14 9H18V7H14V9ZM18 13H14V11H18V13ZM6 15V17L18 17V15L6 15Z"></path></svg>'
                ],
                [
                    'name' => 'البرامج',
                    'count' => $programsCount,
                    'addRoute' => route('admin.programs.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H21ZM20 11H4V19H20V11ZM20 5H4V9H20V5ZM11 6V8H9V6H11ZM7 6V8H5V6H7Z"></path></svg>'
                ],
                [
                    'name' => 'المشاريع',
                    'count' => $projectsCount,
                    'addRoute' => route('admin.projects.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 19H23V21H1V19H3V4C3 3.44772 3.44772 3 4 3H14C14.5523 3 15 3.44772 15 4V19H19V11H17V9H20C20.5523 9 21 9.44772 21 10V19ZM5 5V19H13V5H5ZM7 11H11V13H7V11ZM7 7H11V9H7V7Z"></path></svg>'
                ],
                [
                    'name' => 'الإعلانات',
                    'count' => $announcementsCount,
                    'addRoute' => route('admin.announcements.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 17H22V19H2V17H4V10C4 5.58172 7.58172 2 12 2C16.4183 2 20 5.58172 20 10V17ZM18 17V10C18 6.68629 15.3137 4 12 4C8.68629 4 6 6.68629 6 10V17H18ZM9 21H15V23H9V21Z"></path></svg>'
                ],
                [
                    'name' => 'المستندات',
                    'count' => $documentsCount,
                    'addRoute' => route('admin.documents.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 2.9918C3 2.44405 3.44495 2 3.9934 2H20.0066C20.5552 2 21 2.45531 21 2.9918V21.0082C21 21.556 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918ZM19 11V4H5V11H19ZM19 13H5V20H19V13ZM9 6H15V8H9V6ZM9 15H15V17H9V15Z"></path></svg>'
                ],
                [
                    'name' => 'معرض الصور',
                    'count' => $galleriesCount,
                    'addRoute' => route('admin.galleries.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 13C18.3221 13 16.7514 13.4592 15.4068 14.2587C16.5908 15.6438 17.5269 17.2471 18.1465 19H20V13ZM16.0037 19C14.0446 14.3021 9.4079 11 4 11V19H16.0037ZM4 9C7.82914 9 11.3232 10.4348 13.9738 12.7961C15.7047 11.6605 17.7752 11 20 11V3H21.0082C21.556 3 22 3.44495 22 3.9934V20.0066C22 20.5552 21.5447 21 21.0082 21H2.9918C2.44405 21 2 20.5551 2 20.0066V3.9934C2 3.44476 2.45531 3 2.9918 3H6V1H8V5H4V9ZM18 1V5H10V3H16V1H18ZM16.5 10C15.6716 10 15 9.32843 15 8.5C15 7.67157 15.6716 7 16.5 7C17.3284 7 18 7.67157 18 8.5C18 9.32843 17.3284 10 16.5 10Z"></path></svg>'
                ],
                [
                    'name' => 'قصص النجاح',
                    'count' => $storiesCount,
                    'addRoute' => route('admin.stories.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 15.2454V22.1169C17 22.393 16.7761 22.617 16.5 22.617C16.4094 22.617 16.3205 22.5923 16.2428 22.5457L12 20L7.75725 22.5457C7.52046 22.6877 7.21333 22.6109 7.07125 22.3742C7.02463 22.2964 7 22.2075 7 22.1169V15.2454C5.17107 13.7793 4 11.5264 4 9C4 4.58172 7.58172 1 12 1C16.4183 1 20 4.58172 20 9C20 11.5264 18.8289 13.7793 17 15.2454ZM9 16.4185V19.4676L12 17.6676L15 19.4676V16.4185C14.0736 16.7935 13.0609 17 12 17C10.9391 17 9.92643 16.7935 9 16.4185ZM12 15C15.3137 15 18 12.3137 18 9C18 5.68629 15.3137 3 12 3C8.68629 3 6 5.68629 6 9C6 12.3137 8.68629 15 12 15Z"></path></svg>'
                ],
                [
                    'name' => 'الفيديو',
                    'count' => $videosCount,
                    'addRoute' => route('admin.videos.create'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 19V5H9.58579L11.5858 7H20V19H4ZM21 5H12.4142L10.4142 3H3C2.44772 3 2 3.44772 2 4V20C2 20.5523 2.44772 21 3 21H21C21.5523 21 22 20.5523 22 20V6C22 5.44772 21.5523 5 21 5ZM15.0008 12.667L10.1219 9.41435C10.0562 9.37054 9.979 9.34717 9.9 9.34717C9.6791 9.34717 9.5 9.52625 9.5 9.74717V16.2524C9.5 16.3314 9.5234 16.4086 9.5672 16.4743C9.6897 16.6581 9.9381 16.7078 10.1219 16.5852L15.0008 13.3326C15.0447 13.3033 15.0824 13.2656 15.1117 13.2217C15.2343 13.0379 15.1846 12.7895 15.0008 12.667Z"></path></svg>'
                ],
            ]
        @endphp

        @foreach ($stats as $stat)
            <div class="stat-item">
                <div class="icon">
                    {!! $stat['icon'] !!}
                </div>

                <div class="title">
                    {{ $stat['name'] }}
                </div>

                <div class="count">
                    {{ $stat['count'] }}
                </div>

                <a href="{{ $stat['addRoute'] }}" class="add-new flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM11 11H7V13H11V17H13V13H17V11H13V7H11V11Z"></path></svg>
                    <span>إضافة جديد</span>
                </a>
            </div>
        @endforeach
    </div>
@endsection

@push('styles')
    <style>
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
            padding-bottom: 1.25rem;
        }
        .stat-item {
            background-color: var(--card-bg);
            padding: 20px;
            border: 1px solid var(--divider);
            border-radius: 5px;
        }
        .stat-item .icon {
            width: 40px;
        }
        .stat-item .icon > svg {
            display: block;
        }
        .stat-item .title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .stat-item .count {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .stat-item .add-new {
            color: var(--primary);
            font-size: 1.125rem;
            gap: 3px;
        }
        .stat-item .add-new:hover {
            text-decoration: underline;
        }
        .stat-item .add-new > svg {
            width: 20px;
        }
    </style>
@endpush