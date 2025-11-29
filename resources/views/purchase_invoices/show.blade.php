<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض بيانات فاتورة الشراء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- بيانات الفاتورة --}}
                <h3 class="section-title">بيانات الفاتورة</h3>

                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">رقم الفاتورة</span>
                        <span class="info-content">{{ $invoice->id }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">تاريخ الفاتورة</span>
                        <span class="info-content">{{ $invoice->invoice_date }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المورد</span>
                        <span class="info-content">{{ $invoice->supplier?->name ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الصندوق</span>
                        <span class="info-content">{{ $invoice->cashBox?->name ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المخزن</span>
                        <span class="info-content">{{ $invoice->warehouse?->name ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">طريقة الدفع</span>
                        <span class="info-content">
                            {{ $invoice->payment_status == 'cash' ? 'نقد' : 'آجل' }}
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المبلغ المدفوع</span>
                        <span class="info-content">{{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المبلغ المتبقي</span>
                        <span class="info-content">{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</span>
                    </div>
                </div>

                {{-- جدول القطع --}}
                @if($invoice->parts?->isNotEmpty())
                    <h3 class="section-title">القطع</h3>

                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>القطعة</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>الإجمالي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoice->parts as $part)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $part->part?->name ?? '-' }}</td>
                                        <td>{{ $part->quantity }}</td>
                                        <td>{{ number_format($part->purchase_price, 2) }}</td>
                                        <td>{{ number_format($part->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold">
                                    <td colspan="4" class="text-right">الإجمالي الفرعي</td>
                                    <td>{{ number_format($invoice->parts->sum('total_price'), 2) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- جدول الأجهزة --}}
                @if($invoice->items?->isNotEmpty())
                    <h3 class="section-title">الأجهزة</h3>

                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصنف</th>
                                    <th>الوحدة</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>الإجمالي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoice->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->item?->name ?? '-' }}</td>
                                        <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold">
                                    <td colspan="5" class="text-right">الإجمالي الفرعي</td>
                                    <td>{{ number_format($invoice->items->sum('total_price'), 2) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif


                <div class="mt-6">
                    <a href="{{ route('purchase_invoices.index') }}" class="btn btn-primary">العودة للفواتير</a>
                    <a href="{{ route('purchase_invoices.edit', $invoice->id) }}" class="btn btn-secondary">تعديل</a>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
