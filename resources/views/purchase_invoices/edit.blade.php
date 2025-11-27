<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تـعـديــل فـاتــورة مشتريات رقم {{ $invoice->id }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/repair_invoice.css') }}">

    <div class="py-12">

        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('purchase_invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- بيانات الفاتورة --}}
            <div class="row-2">

                <div class="form-group">
                    <label>اختر المورد</label>
                    <select name="supplier_id" id="supplier_id">
                        <option value="0" {{ $invoice->supplier_id == 0 ? 'selected' : '' }}>
                            مورد نقدي
                        </option>

                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ $invoice->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>اختر المخزن</label>
                    <select name="warehouse_id">
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                {{ $invoice->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>تاريخ الفاتورة</label>
                    <input type="date" name="invoice_date"
                           value="{{ old('invoice_date', $invoice->invoice_date) }}">
                </div>

                <div class="form-group">
                    <label>نوع الدفع</label>
                    <select name="payment_type" id="payment_type">
                        <option value="cash" {{ $invoice->payment_status == 'cash' ? 'selected' : '' }}>
                            نقدي
                        </option>
                        <option value="credit" {{ $invoice->payment_status == 'credit' ? 'selected' : '' }}>
                            آجل
                        </option>
                    </select>
                </div>
            </div>


            {{-- جدول الأجزاء --}}
            <div class="parts-section">
                <h3>قطع الشراء</h3>
                <table class="parts-table">
                    <thead>
                    <tr>
                        <th>القطعة</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>الإجمالي</th>
                        <th>إضافة / حذف</th>
                    </tr>
                    </thead>

                    <tbody id="parts-table">

                    @foreach($invoice->parts as $index => $item)
                        <tr>
                            <td class="td-select">
                                <select name="parts[{{ $index }}][part_id]" class="part-select">
                                    <option value="">اختر القطعة</option>
                                    @foreach($parts as $part)
                                        <option value="{{ $part->id }}"
                                                data-price="{{ $part->purchase_price }}"
                                            {{ $item->part_id == $part->id ? 'selected' : '' }}>
                                            {{ $part->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="parts[{{ $index }}][quantity]"
                                       value="{{ $item->quantity }}"
                                       class="qty-input" min="1">
                            </td>

                            <td>
                                <input type="number" name="parts[{{ $index }}][purchase_price]"
                                       value="{{ $item->purchase_price }}"
                                       class="price-input">
                            </td>

                            <td>
                                <input type="number" name="parts[{{ $index }}][total_price]"
                                       value="{{ $item->total_price }}"
                                       class="total-input" readonly>
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger remove-row">-</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                {{-- زر لإضافة سطر جديد --}}
                <button type="button" id="add-row" class="btn btn-primary mt-2">إضافة سطر</button>
            </div>


            {{-- ملخص الفاتورة --}}
            <div class="row-3">

                <div class="form-group">
                    <label>إجمالي الفاتورة</label>
                    <input type="number" name="total_amount" id="total_amount"
                           value="{{ $invoice->total_amount }}" readonly>
                </div>

                <div class="form-group">
                    <label>المدفوع</label>
                    <input type="number" name="paid_amount" id="paid_amount"
                           value="{{ $invoice->paid_amount }}">
                </div>

                <div class="form-group">
                    <label>المتبقي</label>
                    <input type="number" name="remaining_amount" id="remaining_amount"
                           value="{{ $invoice->remaining_amount }}" readonly>
                </div>

            </div>

            <div class="actions">
                <a href="{{ route('purchase_invoices.index') }}" class="btn-secondary">عودة</a>
                <button type="submit" class="btn-primary">حفظ التعديلات</button>
            </div>

        </form>
    </div>

    <script>
        window.parts = @json($parts);
        window.lastIndex = {{ $invoice->parts->count() }};
    </script>

    <script src="{{ asset('js/purchase.js') }}"></script>

</x-app-layout>
