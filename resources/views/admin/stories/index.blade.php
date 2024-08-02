@extends('admin.layout.app', [
    'title' => 'قصص النجاح | لوحة التحكم'
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
                <h1 class="card-title">تصفح قصص النجاح</h1>
                
                <div class="flex align-items-center g-0.5rem">
                    @include('admin.partials.lang-select')

                    @can('add-story')
                        <a href="{{ route('admin.stories.create') }}" class="btn btn-fill btn-primary">إضافة قصة نجاح</a>
                    @endcan
                </div>
            </div>

            <div class="card-navigation flex justify-content-between align-items-center">
                <form action="{{ route('admin.stories.search') }}" class="search-form">
                    <input type="hidden" name="lang" value="{{ $currentLang }}">
                    <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="بحث">
                </form>

                {{ $stories->links('pagination::default') }}
            </div>
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>الصورة</th>
                            <th>البرنامج</th>
                            <th>المشروع</th>
                            <th>الحالة</th>
                            <th>مميز</th>
                            <th>تاريخ الإضافة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($stories as $item)
                            <tr>
                                <td>{{ $item->translate($currentLang)->title }}</td>

                                <td>
                                    <img class="table-preview" src="{{ asset('storage/' . $item->image) }}" alt="">
                                </td>

                                <td>{{ $item->program->translate($currentLang)->title ?? 'لا يوجد' }}</td>

                                <td>{{ $item->project->translate($currentLang)->title ?? 'لا يوجد' }}</td>

                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $item->status ? 'فعال' : 'متوقف',
                                        'color' => $item->status ? 'success' : 'danger'    
                                    ])
                                </td>

                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $item->featured ? 'فعال' : 'متوقف',
                                        'color' => $item->featured ? 'success' : 'danger'    
                                    ])
                                </td>
                                
                                <td>{{ Carbon\Carbon::parse($item->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}</td>

                                <td>
                                    <div class="flex table-actions">
                                        @can('edite-story')
                                            @include('admin.partials.edit-action', ['route' => route('admin.stories.edit', $item)])
                                        @endcan

                                        @can('delete-story')
                                            @include('admin.partials.delete-action', ['target' => '#delete-confirm-' . $item->id])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $item->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف قصة النجاح "{{ $item->translate($currentLang)->title }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.stories.destroy', $item) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-danger btn-fill" type="submit">حذف</button>
                                                    </form>
                                                    <button type="button" class="dialog-dismiss btn">الغاء</button>
                                                </div>
                                            </dialog>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data">
                                <td colspan="8" class="text-center">لا توجد سجلات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection