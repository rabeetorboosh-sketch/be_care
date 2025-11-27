<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">فواتير الصيانة</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        <div class="btn btn-primary mb-4">
            <a href="{{ route('repair_invoices.create') }}">
                + إنشاء فاتورة
            </a>
        </div>


            @if(session('success'))
                <div id="success-alert" class="alert-success">
                    {{ session('success') }}
                </div>
            @endif


        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>الجهاز</th>
                        <th>نوع الجهاز</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $invoice->customer_id == 0 ? 'عميل نقدي' : $invoice->customer?->name }}</td>
                            <td>{{ ($invoice->device?->device_code ?? '') . '-' . ($invoice->device?->type?->name ?? '') }}</td>
                            <td>{{ $invoice->type?->name ?? '-' }}</td>
                            @php
                                $statusLabels = [
                                    'ready' => 'جاهز',
                                    'in_progress' => 'جاري الصيانة',
                                    'delivered' => 'تم التسليم',
                                ];
                            @endphp

                            <td>{{ $statusLabels[$invoice->status] ?? '-' }}</td>
                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="text-green-700">{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="text-red-700">{{ number_format($invoice->remaining_amount, 2) }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('repair_invoices.show', $invoice->id) }}" class="btn btn-info">عرض</a>
                                    <a href="{{ route('repair_invoices.edit', $invoice->id) }}" class="btn btn-warning">تعديل</a>

                                    <form action="{{ route('repair_invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="if(confirm('هل تريد حذف الفاتورة؟')) this.form.submit();">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 py-4">لا توجد فواتير مسجلة</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $invoices->links() }}
        </div>

    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
