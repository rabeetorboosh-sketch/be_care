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
                        <span class="info-title">المخزن </span>
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
                        <span class="info-content">{{ number_format(number_format($invoice->total_amount, 2)-number_format($invoice->paid_amount, 2), 2) }}</span>
                    </div>

                </div>



                {{-- جدول الأصناف --}}
                @if($invoice->parts?->isNotEmpty())
                    <h3 class="section-title">الأصناف</h3>

                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصنف</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>الإجمالي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoice->parts as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->part?->name ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->purchase_price, 2) }}</td>
                                        <td>{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
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
