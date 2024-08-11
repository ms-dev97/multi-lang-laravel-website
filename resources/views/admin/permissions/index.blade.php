@extends('admin.layout.app', [
    'title' => 'الاذونات | لوحة التحكم'
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
                <h1 class="card-title">تصفح الاذونات</h1>
                
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-fill btn-primary">إضافة جديد</a>
            </div>

            <div class="card-navigation flex justify-content-between align-items-center">
                <form action="{{ route('admin.permissions.search') }}" class="search-form">
                    <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="بحث">
                </form>

                {{ $permissions->links('pagination::default') }}
            </div>
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>اسم العرض</th>
                            <th>الجدول</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->display_name }}</td>
                                <td>{{ $permission->table_name }}</td>

                                <td>
                                    <div class="flex table-actions">
                                        @include('admin.partials.edit-action', [
                                            'route' => route('admin.permissions.edit', $permission)
                                        ])

                                        @include('admin.partials.delete-action', ['target' => '#delete-confirm-' . $permission->id])

                                        <dialog class="delete-confirm dialog" id="delete-confirm-{{ $permission->id }}">
                                            <div class="dialog-header">تأكيد الحذف</div>
                                            <div class="dialog-body">
                                                هل أنت متأكد من أنك تريد حذف هذا الاذن "{{ $permission->name }}"؟
                                            </div>
                                            <div class="dialog-footer">
                                                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-danger btn-fill" type="submit">حذف</button>
                                                </form>
                                                <button type="button" class="dialog-dismiss btn">الغاء</button>
                                            </div>
                                        </dialog>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data">
                                <td colspan="4" class="text-center">لا توجد سجلات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection