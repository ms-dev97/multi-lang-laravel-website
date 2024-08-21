@extends('admin.layout.app', [
    'title' => 'المستندات | لوحة التحكم'
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
                <h1 class="card-title">تصفح المستندات</h1>

                <div class="flex align-items-center g-0.5rem">
                    @include('admin.partials.lang-select')

                    @can('add-document')
                        <a href="{{ route('admin.documents.create') }}" class="btn btn-fill btn-primary">اضافة جديد</a>
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
                            <th>القسم</th>
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
                                <td>{{ $document->translate($currentLang)->title }}</td>

                                <td>
                                    <img class="table-preview" src="{{ asset('storage/' . $document->image) }}" alt="">
                                </td>

                                <td>{{ $document->category?->translate($currentLang, true)->title ?? 'لا يوجد' }}</td>
                                <th>
                                    @php
                                        $docLink = $document->get_from_link ? $document->link : asset('storage/'.$document->path);
                                    @endphp
                                    <div class="table-actions flex">
                                        <a class="action" href="{{ $docLink }}" target="_blank">
                                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 464l48 0 0 48-48 0c-35.3 0-64-28.7-64-64L0 64C0 28.7 28.7 0 64 0L229.5 0c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3L384 304l-48 0 0-144-80 0c-17.7 0-32-14.3-32-32l0-80L64 48c-8.8 0-16 7.2-16 16l0 384c0 8.8 7.2 16 16 16zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg>
                                        </a>
                                    </div>
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
                                        @can('read-document')
                                            @include('admin.partials.show-action', [
                                                'route' => route('admin.documents.show', [$document, 'lang' => $currentLang])
                                            ])
                                        @endcan

                                        @can('edit-document')
                                            @include('admin.partials.edit-action', [
                                                'route' => route('admin.documents.edit', [$document, 'lang' => $currentLang])
                                            ])
                                        @endcan

                                        @can('delete-document')
                                            @include('admin.partials.delete-action', [
                                                'target' => '#delete-confirm-' . $document->id
                                            ])

                                            <dialog class="delete-confirm dialog" id="delete-confirm-{{ $document->id }}">
                                                <div class="dialog-header">تأكيد الحذف</div>
                                                <div class="dialog-body">
                                                    هل أنت متأكد من أنك تريد حذف هذا المستند "{{ $document->translate($currentLang)->title }}"؟
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