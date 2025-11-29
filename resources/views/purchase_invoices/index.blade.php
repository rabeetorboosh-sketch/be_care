<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">فواتير الشراء</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        <div class="btn btn-primary mb-4">
            <a href="{{ route('purchase_invoices.create') }}">
                + إنشاء فاتورة شراء
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
                        <th>المورد</th>
                        <th>تاريخ الفاتورة</th>
                        <th>الإجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>نوع الدفع</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $invoice->supplier?->name ?? '-' }}</td>
                            <td>{{ $invoice->invoice_date }}</td>

                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="text-green-700">{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td class="text-red-700">{{  number_format($invoice->total_amount - $invoice->paid_amount, 2)}}</td>

                            <td>
                                {{ $invoice->payment_status == 'cash' ? 'نقدي' : 'آجل' }}
                            </td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('purchase_invoices.show', $invoice->id) }}" class="btn btn-info">عرض</a>
                                    <a href="{{ route('purchase_invoices.edit', $invoice->id) }}" class="btn btn-warning">تعديل</a>

                                    <form action="{{ route('purchase_invoices.destroy', $invoice->id) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                                onclick="if(confirm('هل تريد حذف الفاتورة؟')) this.form.submit();">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">لا توجد فواتير شراء مسجلة</td>
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
