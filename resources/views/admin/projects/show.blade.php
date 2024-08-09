@extends('admin.layout.app', [
    'title' => $projectTrans->title
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
                'type' => 'danger'    
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
                <div class="header-title">تفاصيل المشروع</div>
                <a href="{{ route('admin.projects.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">العنوان</div>
                <div class="show-field-value">{{ $projectTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصورة</div>
                <div class="show-field-value">
                    @if (!is_null($project->image))
                        <img src="{{ asset('storage/'.$project->image) }}" alt="">
                    @else
                        لا يوجد صورة
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">صورة البانر</div>
                <div class="show-field-value">
                    @if (!is_null($project->cover))
                        <img src="{{ asset('storage/'.$project->cover) }}" alt="">
                    @else
                        لا يوجد صورة
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{!! $projectTrans->body !!}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">البرنامج</div>
                <div class="show-field-value">{{ $project->program->translate($currentLang, true)?->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">معرض الصور</div>
                <div class="flex g-0.5rem show-gallery">
                    @forelse ($project->gallery as $item)
                        <img class="gallery-img" src="{{ $item }}" class="gallery-img">
                    @empty
                        لا توجد صور
                    @endforelse
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($project->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($project->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edite-project')
                <a href="{{ route('admin.projects.edit', [$project, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection