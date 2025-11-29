    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                عرض سند صرف
            </h2>
        </x-slot>

        <link rel="stylesheet" href="{{ asset('css/show.css') }}">
        <link rel="stylesheet" href="{{ asset('css/table.css') }}">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="report-container">

                    {{-- بيانات السند --}}
                    <h3 class="section-title">بيانات السند</h3>

                    <div class="info-grid">

                        <div class="info-card">
                            <span class="info-title">رقم السند</span>
                            <span class="info-content">{{ $receipt->id }}</span>
                        </div>

                        <div class="info-card">
                            <span class="info-title">نوع السند</span>
                            <span class="info-content">صرف</span>
                        </div>

                        <div class="info-card">
                            <span class="info-title">الجهة</span>
                            <span class="info-content">{{ $receipt->accountable?->name ?? '-' }}</span>
                        </div>

                        <div class="info-card">
                            <span class="info-title">الصندوق</span>
                            <span class="info-content">{{ $receipt->cashBox->name ?? '-' }}</span>
                        </div>

                        <div class="info-card">
                            <span class="info-title">المبلغ</span>
                            <span class="info-content">{{ number_format($receipt->amount,2) }}</span>
                        </div>

                        <div class="info-card">
                            <span class="info-title">الوصف</span>
                            <span class="info-content">{{ $receipt->description ?? '-' }}</span>
                        </div>

                        <div class="info-card">
                            <span class="info-title">التاريخ</span>
                            <span class="info-content">{{ $receipt->created_at->format('Y-m-d h:i A') }}</span>
                        </div>

                    </div>

                    {{-- زر الرجوع --}}
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('receipts_out.edit', $receipt->id) }}" class="btn btn-primary">
                            تعديل السند
                        </a>

                        <a href="{{ route('receipts_out.index') }}" class="btn btn-secondary">
                            العودة للسندات
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </x-app-layout>
