@extends('admin.layout.app', [
    'title' => 'الرسائل | لوحة التحكم'
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
            <h1 class="card-title">تصفح الرسائل</h1>
        </div>

        <div class="card-navigation flex justify-content-between align-items-center">
            <form action="{{ route('admin.mails.search') }}" class="search-form">
                <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="بحث">
            </form>

            {{ $mails->links('pagination::default') }}
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الموضوع</th>
                            <th>رقم الهاتف</th>
                            <th>حالة القراءة</th>
                            <th>الحالة</th>
                            <th>تاريخ الإضافة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($mails as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->subject }}</td>
                                <td>{{ $item->phone_number }}</td>
                                <td>
                                    @if ($item->is_read)
                                        مقروء
                                    @else
                                        غير مقروء
                                    @endif
                                </td>
                                <td>{{ $item->status }}</td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}</td>
                                <td>
                                    <div class="flex table-actions">
                                        @can('read-mail')
                                            @include('admin.partials.show-action', [
                                                'route' => route('admin.mails.show', $item)
                                            ])
                                        @endcan

                                        @can('delete-mail')
                                            @include('admin.partials.delete-action', ['target' => '#delete-confirm-' . $item->id])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $item->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف الرسالة "{{ $item->name }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.mails.destroy', $item) }}" method="post">
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
                                <td colspan="6" class="text-center">لا توجد سجلات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection