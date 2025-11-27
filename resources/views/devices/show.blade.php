<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض بيانات الجهاز
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">بيانات الجهاز</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">كود الجهاز</span>
                        <span class="info-content">{{ $device->device_code }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">نوع الجهاز</span>
                        <span class="info-content">
                            {{ $device->type->name ?? 'غير محدد' }} - {{ $device->type->brand ?? '—' }}
                        </span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">العميل</span>
                        <span class="info-content">{{ $device->customer->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">{{ $device->status }}</span>
                    </div>
                    @if($device->notes)
                        <div class="info-card">
                            <span class="info-title">ملاحظات</span>
                            <span class="info-content">{{ $device->notes }}</span>
                        </div>
                    @endif
                </div>

                {{-- الفواتير المرتبطة --}}
                @if($device->invoices->isNotEmpty())
                    <h3 class="section-title">الفواتير المرتبطة بالجهاز</h3>
                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الفاتورة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الحالة</th>
                                    <th>الإجمالي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($device->invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $invoice->invoice_number ?? '-' }}</td>
                                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $invoice->status ?? '-' }}</td>
                                        <td>{{ $invoice->total ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('devices.index') }}" class="btn btn-primary">العودة للأجهزة</a>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
