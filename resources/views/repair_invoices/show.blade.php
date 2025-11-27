<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض بيانات فاتورة الصيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">بيانات الفاتورة</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">رقم الفاتورة</span>
                        <span class="info-content">{{ $invoice->id }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        @php
                            $statusLabels = [
                                'ready' => 'جاهز',
                                'in_progress' => 'جاري الصيانة',
                                'delivered' => 'تم التسليم',
                                'received' => ' مستلم ',
                            ];
                        @endphp


                        <span class="info-content">{{  $statusLabels[$invoice->status] }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">طريقة الدفع</span>
                        <span class="info-content">{{ $invoice->payment_status=='cash'?'نقد':'اجل' ?? '-' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">المبلغ المدفوع</span>
                        <span class="info-content">{{ $invoice->paid_amount ?? 0 }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">المبلغ المتبقي</span>
                        <span class="info-content">{{ $invoice->remaining_amount ?? 0 }}</span>
                    </div>
                </div>

                <h3 class="section-title">بيانات الجهاز</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">كود الجهاز</span>
                        <span class="info-content">{{ $invoice->device?->device_code ?? '-' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">نوع الجهاز</span>
                        <span class="info-content">
                             {{ $invoice->device?->type?->name  ?? $invoice->type?->name ?? 'غير محدد' }}
                        </span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">العميل</span>
                        <span class="info-content">{{ $invoice->customer?->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">رسوم الخدمة</span>
                        <span class="info-content">{{ $invoice->service_fee }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">إجمالي قطع الغيار</span>
                        <span class="info-content">{{ $invoice->total_parts_price }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الإجمالي الكلي</span>
                        <span class="info-content">{{ $invoice->total_amount }}</span>
                    </div>
                </div>

                {{-- عرض قطع الغيار --}}
                @if($invoice->parts->isNotEmpty())
                    <h3 class="section-title">قطع الغيار</h3>
                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم القطعة</th>
                                    <th>السعر</th>
                                    <th>الكمية</th>
                                    <th>الإجمالي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invoice->parts as $part)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $part->part?->name ?? '-' }}</td>
                                        <td>{{ $part->unit_price ?? 0 }}</td>
                                        <td>{{ $part->qty ?? 0 }}</td>
                                        <td>{{ $part-> total_price}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('repair_invoices.index') }}" class="btn btn-secondary">العودة للفواتير</a>
                    <a href="{{ route('repair_invoices.edit', $invoice->id) }}" class="btn btn-primary">   تعديل الفاتورة </a>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
