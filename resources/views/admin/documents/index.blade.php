@extends('admin.layout.app', [
    'title' => 'الوثائق | لوحة التحكم'
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
                <h1 class="card-title">تصفح الوثائق</h1>

                <div class="flex align-items-center g-0.5rem">
                    @include('admin.partials.lang-select')

                    @can('add-document')
                        <a href="{{ route('admin.documents.create') }}" class="btn btn-fill btn-primary">اضافة وثيقة</a>
                    @endcan
                </div>
            </div>

            <div class="card-navigation flex justify-content-between align-items-center">
                <form action="{{ route('admin.documents.search') }}" class="search-form">
                    <input type="hidden" name="lang" value="{{ $currentLang }}">
                    <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="بحث">
                </form>

                {{ $documents->links('pagination::default') }}
            </div>
        </div>

        <div class="card-body">
            <div class="table records-table">
                <table>
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الصورة</th>
                            <th>الملف</th>
                            <th>الحالة</th>
                            <th>مميز</th>
                            <th>تاريخ الإضافة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>{{ $document->translate($currentLang, true)->title }}</td>

                                <td>
                                    <img class="table-preview" src="{{ asset('storage/' . $document->image) }}" alt="">
                                </td>

                                <th>
                                    @php
                                        $docLink = $document->get_from_link ? $document->link : asset('storage/'.$document->path);
                                    @endphp
                                    <a href="{{ $docLink }}">
                                        الملف
                                    </a>
                                </th>

                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $document->status ? 'فعال' : 'متوقف',
                                        'color' => $document->status ? 'success' : 'danger'    
                                    ])
                                </td>
                                <td>
                                    @include('admin.partials.bill', [
                                        'text' => $document->featured ? 'فعال' : 'متوقف',
                                        'color' => $document->featured ? 'success' : 'danger'    
                                    ])
                                </td>
                                <td>{{ Carbon\Carbon::parse($document->created_at)->locale('ar')->isoFormat('Do MMMM YYYY') }}</td>
                                <td>
                                    <div class="flex table-actions">
                                        @can('edit-document')
                                            @include('admin.partials.edit-action', [
                                                'route' => route('admin.documents.edit', $document)
                                            ])
                                        @endcan

                                        @can('delete-document')
                                            @include('admin.partials.delete-action', [
                                                'target' => '#delete-confirm-' . $document->id
                                            ])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $document->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف هذه الوثيقة "{{ $document->translate($currentLang)->title }}"؟
                                                </div>
                                                <div class="dialog-footer">
                                                    <form action="{{ route('admin.documents.destroy', $document) }}" method="post">
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