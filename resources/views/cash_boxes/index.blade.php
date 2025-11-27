<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            الصناديق النقدية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="btn btn-primary mb-4">
            <a href="{{ route('cash_boxes.create') }}">إضافة صندوق جديد <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الصندوق</th>
                        <th>الرصيد الافتتاحي</th>
                        <th>الحالة</th>
                        <th>الصندوق الرئيسي</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cashBoxes as $box)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $box->name }}</td>
                            <td>{{ $box->opening_balance }}</td>
                            <td>
                                @if($box->is_active)
                                    <span class="status status-success">نشط</span>
                                @else
                                    <span class="status status-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>{{ $box->is_main ? 'نعم' : 'لا' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('cash_boxes.show', $box->id) }}" class="btn btn-info">عرض</a>
                                    <a href="{{ route('cash_boxes.edit', $box->id) }}" class="btn btn-warning">تعديل</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">لا يوجد صناديق حالياً</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
