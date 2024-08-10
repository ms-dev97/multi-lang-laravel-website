@extends('admin.layout.app', [
    'title' => $storyTrans->title
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
                <div class="header-title">تفاصيل قصة النجاح</div>
                <a href="{{ route('admin.stories.index') }}">عودة</a>
            </div>
        </div>

        <div class="card-body">
            @include('admin.partials.lang-select', [
                'currentLang' => $currentLang,
                'langs' => $langs,
            ])

            <div class="show-field">
                <div class="show-field-name">العنوان</div>
                <div class="show-field-value">{{ $storyTrans->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الصورة</div>
                <div class="show-field-value">
                    @if (!is_null($story->image))
                        <img src="{{ asset('storage/'.$story->image) }}" alt="">
                    @else
                        لا يوجد صورة
                    @endif
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">الوصف المختصر</div>
                <div class="show-field-value">{{ $storyTrans->excerpt }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المحتوى</div>
                <div class="show-field-value">{!! $storyTrans->body !!}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">البرنامج</div>
                <div class="show-field-value">{{ $story->program->translate($currentLang, true)?->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">المشروع</div>
                <div class="show-field-value">{{ $story->project->translate($currentLang, true)?->title }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">نوع القصة</div>
                <div class="show-field-value">
                    @switch($story->type)
                        @case(1)
                            قصة مقروءة
                            @break
                        @case(2)
                            قصة فيديو
                        @default
                            قصة مقروءة
                    @endswitch
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">رابط الفيديو</div>
                <div class="show-field-value">{{ $story->link ?? 'لا يوجد' }}</div>
            </div>

            <div class="show-field">
                <div class="show-field-name">معرض الصور</div>
                <div class="flex g-0.5rem show-gallery">
                    @forelse ($story->gallery as $item)
                        <img class="gallery-img" src="{{ $item }}" class="gallery-img">
                    @empty
                        لا توجد صور
                    @endforelse
                </div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ الاضافة</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($story->created_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            <hr>

            <div class="show-field">
                <div class="show-field-name">تاريخ التعديل</div>
                <div class="show-field-value">{{ Carbon\Carbon::parse($story->updated_at)->locale($currentLang)->isoFormat('Do MMMM YYYY') }}</div>
            </div>

            @can('edite-story')
                <a href="{{ route('admin.stories.edit', [$story, 'lang' => $currentLang]) }}" class="btn btn-primary btn-fill">
                    تعديل
                </a>
            @endcan
        </div>
    </div>
@endsection