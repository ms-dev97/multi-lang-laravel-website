@extends('admin.layout.app', [
    'title' => 'الشركاء | لوحة التحكم'
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
                <h1 class="card-title">تصفح الشركاء</h1>
                
                <div class="flex align-items-center g-0.5rem">
                    @include('admin.partials.lang-select')

                    @can('add-partner')
                        <a href="{{ route('admin.partners.create') }}" class="btn btn-fill btn-primary">إضافة جديد</a>
                    @endcan
                </div>
            </div>

            <div class="card-navigation flex justify-content-between align-items-center">
                {{ $partners->links('pagination::default') }}
            </div>
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الصورة</th>
                            <th>الترتيب</th>
                            <th>مميز</th>
                            <th>تاريخ الإضافة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($partners as $item)
                            <tr>
                                <td>{{ $item->translate($currentLang)->name }}</td>

                                <td>
                                    <img class="table-preview" src="{{ asset('storage/' . $item->image) }}" alt="">
                                </td>

                                <td>{{ $item->order }}</td>

                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $item->status ? 'فعال' : 'متوقف',
                                        'color' => $item->status ? 'success' : 'danger'    
                                    ])
                                </td>
                                
                                <td>{{ Carbon\Carbon::parse($item->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}</td>

                                <td>
                                    <div class="flex table-actions">
                                        @can('read-partner')
                                            @include('admin.partials.show-action', ['route' => route('admin.partners.show', $item)])
                                        @endcan

                                        @can('edite-partner')
                                            @include('admin.partials.edit-action', ['route' => route('admin.partners.edit', $item)])
                                        @endcan

                                        @can('delete-partner')
                                            @include('admin.partials.delete-action', ['target' => '#delete-confirm-' . $item->id])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $item->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف  "{{ $item->translate($currentLang)->name }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.partners.destroy', $item) }}" method="post">
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