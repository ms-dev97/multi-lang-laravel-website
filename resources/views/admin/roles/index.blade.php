@extends('admin.layout.app', [
    'title' => 'الأدوار | لوحة التحكم'
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
            <h1 class="card-title">تصفح الأدوار</h1>
            @can('add-role')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-fill btn-primary">إضافة جديد</a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>اسم العرض</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->display_name }}</td>

                                <td>
                                    <div class="flex table-actions">
                                        @can('read-role')
                                            @include('admin.partials.show-action', ['route' => route('admin.roles.show', $role)])
                                        @endcan

                                        @can('edit-role')
                                            @include('admin.partials.edit-action', [
                                                'route' => route('admin.roles.edit', $role)
                                            ])
                                        @endcan

                                        @can('delete-role')
                                            @include('admin.partials.delete-action', [
                                                'target' => '#delete-confirm-' . $role->id
                                            ])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $role->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف هذا الدور "{{ $role->name }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="post">
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
                                <td colspan="3" class="text-center">لا توجد سجلات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection