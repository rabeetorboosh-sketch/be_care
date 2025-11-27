<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">القطع</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="btn btn-primary">
            <a href="{{ route('parts.create') }}">
                إضافة <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($parts as $part)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $part->name }}</td>
                            <td>{{ $part->purchase_price }}</td>
                            <td>{{ $part->selling_price }}</td>
                            <td>
                                @if($part->is_active)
                                    <span class="status status-success">نشط</span>
                                @else
                                    <span class="status status-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('parts.edit', $part->id) }}" class="btn btn-warning">تعديل</a>

                                    <form action="{{ route('parts.destroy', $part->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="if(confirm('هل أنت متأكد؟')) this.form.submit();">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">لا توجد قطع حالياً</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
