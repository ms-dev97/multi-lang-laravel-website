@extends('admin.layout.app', [
    'title' => 'الأقسام | لوحة التحكم'
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
                <h1 class="card-title">تصفح الأقسام</h1>

                <div class="flex align-items-center g-0.5rem">
                    @include('admin.partials.lang-select')

                    @can('add-category')
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-fill btn-primary">إضافة جديد</a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الحالة</th>
                            <th>مميز</th>
                            <th>تاريخ الإضافة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $cat)
                            <tr>
                                <td>{{ $cat->translate($currentLang)->title }}</td>
                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $cat->status ? 'فعال' : 'متوقف',
                                        'color' => $cat->status ? 'success' : 'danger'    
                                    ])
                                </td>
                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $cat->featured ? 'فعال' : 'متوقف',
                                        'color' => $cat->featured ? 'success' : 'danger'    
                                    ])
                                </td>
                                <td>{{ Carbon\Carbon::parse($cat->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}</td>
                                <td>
                                    <div class="flex table-actions">
                                        @can('read-category')
                                            @include('admin.partials.show-action', ['route' => route('admin.categories.show', $cat)])
                                        @endcan

                                        @can('edit-category')
                                            @include('admin.partials.edit-action', [
                                                'route' => route('admin.categories.edit', $cat)
                                            ])
                                        @endcan

                                        @can('delete-category')
                                            @include('admin.partials.delete-action', [
                                                'target' => '#delete-confirm-' . $cat->id
                                            ])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $cat->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف هذا القسم "{{ $cat->translate($currentLang)->title }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="post">
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
                                <td colspan="5" class="text-center">لا توجد سجلات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection