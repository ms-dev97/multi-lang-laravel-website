@extends('admin.layout.app', [
    'title' => 'المستخدمين | لوحة التحكم'
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
            <h1 class="card-title">تصفح المستخدمين</h1>
            @can('add-user')
                <a href="{{ route('admin.users.create') }}" class="btn btn-fill btn-primary">إضافة جديد</a>
            @endcan
        </div>

        <div class="card-navigation flex justify-content-between align-items-center">
            {{ $users->links('pagination::default') }}
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الايميل</th>
                            <th>الادوار</th>
                            <th>تاريخ الاضافة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}</td>

                                <td>
                                    <div class="flex table-actions">
                                        @can('read-user')
                                            @include('admin.partials.show-action', [
                                                'route' => route('admin.users.show', [$user, 'lang' => $currentLang])
                                            ])
                                        @endcan

                                        @can('edit-user')
                                            @include('admin.partials.edit-action', [
                                                'route' => route('admin.users.edit', [$user, 'lang' => $currentLang])
                                            ])
                                        @endcan

                                        @can('delete-user')
                                            @include('admin.partials.delete-action', [
                                                'target' => '#delete-confirm-' . $user->id
                                            ])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $user->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف هذا المستخدم "{{ $user->name }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="post">
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