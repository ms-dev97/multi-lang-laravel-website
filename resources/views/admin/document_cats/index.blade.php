@extends('admin.layout.app', [
    'title' => 'اقسام الوثائق | لوحة التحكم'
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
            <h1 class="card-title">تصفح اقسام الوثائق</h1>
            @can('add-doc-cat')
                <a href="{{ route('admin.document-categories.create') }}" class="btn btn-fill btn-primary">إضافة قسم</a>
            @endcan
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
                                <td>{{ $cat->translate($lang, true)->title }}</td>
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
                                        @can('edit-doc-cat')
                                            @include('admin.partials.edit-action', [
                                                'route' => route('admin.document-categories.edit', $cat)
                                            ])
                                        @endcan

                                        @can('delete-doc-cat')
                                            @include('admin.partials.delete-action', [
                                                'target' => '#delete-confirm-' . $cat->id
                                            ])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $cat->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف هذا القسم "{{ $cat->translate($lang)->title }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.document-categories.destroy', $cat) }}" method="post">
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