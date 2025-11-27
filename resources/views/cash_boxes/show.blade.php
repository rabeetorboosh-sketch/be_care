<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض صندوق نقدي
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">بيانات الصندوق</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">اسم الصندوق</span>
                        <span class="info-content">{{ $cashBox->name }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الرصيد الافتتاحي</span>
                        <span class="info-content">{{ $cashBox->opening_balance }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">
                            @if($cashBox->is_active)
                                <span class="status status-success">نشط</span>
                            @else
                                <span class="status status-danger">غير نشط</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الصندوق الرئيسي</span>
                        <span class="info-content">{{ $cashBox->is_main ? 'نعم' : 'لا' }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('cash_boxes.index') }}" class="btn-back">العودة للصناديق</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
