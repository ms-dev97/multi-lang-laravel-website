@extends('admin.layout.app', [
    'title' => ' الاعلانات | لوحة التحكم'
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
                <h1 class="card-title">تصفح الاعلانات</h1>

                <div class="flex align-items-center g-0.5rem">
                    @include('admin.partials.lang-select')

                    @can('add-ad')
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-fill btn-primary">إضافة اعلان</a>
                    @endcan
                </div>
            </div>

            <div class="card-navigation flex justify-content-between align-items-center">
                <form action="{{ route('admin.announcements.search') }}" class="search-form">
                    <input type="hidden" name="lang" value="{{ $currentLang }}">
                    <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="بحث">
                </form>

                {{ $announcements->links('pagination::default') }}
            </div>
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الصورة</th>
                            <th>القسم</th>
                            <th>الحالة</th>
                            <th>مميز</th>
                            <th>تاريخ الإضافة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td>{{ $announcement->translate($currentLang, true)->title }}</td>
                                <td><img class="table-preview" src="{{ asset('storage/'.$announcement->image) }}" alt=""></td>
                                <td>{{ $announcement->category?->translate($currentLang, true)->title ?? 'لا يوجد' }}</td>
                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $announcement->status ? 'فعال' : 'متوقف',
                                        'color' => $announcement->status ? 'success' : 'danger'    
                                    ])
                                </td>
                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $announcement->featured ? 'فعال' : 'متوقف',
                                        'color' => $announcement->featured ? 'success' : 'danger'    
                                    ])
                                </td>
                                <td>{{ Carbon\Carbon::parse($announcement->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}</td>
                                <td>
                                    <div class="flex table-actions">
                                        @can('edit-ad')
                                            @include('admin.partials.edit-action', [
                                                'route' => route('admin.announcements.edit', $announcement)
                                            ])
                                        @endcan

                                        @can('delete-ad')
                                            @include('admin.partials.delete-action', [
                                                'target' => '#delete-confirm-' . $announcement->id
                                            ])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $announcement->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف هذا الاعلان "{{ $announcement->translate($currentLang)->title }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="post">
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
                                <td colspan="7" class="text-center">لا توجد سجلات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection