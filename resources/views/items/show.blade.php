<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض الصنف: {{ $item->name }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="report-container">

                <h3 class="section-title">بيانات الصنف</h3>

                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">اسم الصنف</span>
                        <span class="info-content">{{ $item->name }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الشركة</span>
                        <span class="info-content">{{ $item->company ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الإصدار</span>
                        <span class="info-content">{{ $item->version ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">النوع</span>
                        <span class="info-content">
                            @switch($item->type)
                                @case('new') جديد @break
                                @case('used') مستعمل @break
                                @case('refurbished') مجدد @break
                                @default -
                            @endswitch
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">{{ $item->status ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الوحدات</span>
                        <span class="info-content">
                            @forelse($item->units as $unit)
                                {{ $unit->unit?->name ?? '-' }} ({{ $unit->qty }})<br>
                            @empty
                                -
                            @endforelse
                        </span>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-primary">
                        تعديل الصنف
                    </a>

                    <a href="{{ route('items.index') }}" class="btn btn-secondary">
                        العودة للأصناف
                    </a>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
