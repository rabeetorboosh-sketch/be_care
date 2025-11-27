<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض المخزن
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="report-container">

                {{-- بيانات المخزن --}}
                <h3 class="section-title">بيانات المخزن</h3>

                <div class="info-grid">

                    <div class="info-card">
                        <span class="info-title">رقم المخزن</span>
                        <span class="info-content">{{ $warehouse->id }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">اسم المخزن</span>
                        <span class="info-content">{{ $warehouse->name }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الموقع</span>
                        <span class="info-content">{{ $warehouse->location ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">{{ $warehouse->is_active ? 'مفعل' : 'معطل' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المخزن الرئيسي</span>
                        <span class="info-content">{{ $warehouse->is_main ? 'نعم' : 'لا' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">تاريخ الإنشاء</span>
                        <span class="info-content">{{ $warehouse->created_at->format('Y-m-d h:i A') }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">آخر تحديث</span>
                        <span class="info-content">{{ $warehouse->updated_at->format('Y-m-d h:i A') }}</span>
                    </div>

                </div>

                {{-- أزرار الإجراءات --}}
                <div class="mt-6 flex gap-4">
                    <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-primary">تعديل المخزن</a>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">العودة للمخازن</a>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
