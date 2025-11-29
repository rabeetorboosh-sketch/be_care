<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضــافــة فــاتـورة مشتريات
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

        <form class="smart-form" action="{{ route('purchase_invoices.store') }}" method="POST">
            @csrf

            {{-- بيانات الفاتورة --}}
            <div class="row-3">
                <div class="form-group">
                    <label>تاريخ الفاتورة</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label>اختر المورد</label>
                    <select name="supplier_id" id="supplier_id">
                        <option value="0">مورد نقدي</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>اختر المخزن</label>
                    <select name="warehouse_id" id="warehouse_id">
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $warehouse->is_main==1 ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>الصندوق</label>
                    <select name="cash_box_id" required>
                        @foreach($cashBoxes as $box)
                            <option value="{{ $box->id }}" @selected(old('cash_box_id') == $box->id or $box->is_main==1)>
                                {{ $box->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label>نوع الدفع</label>
                    <select name="payment_type" id="payment_type">
                        <option value="cash">نقدي</option>
                        <option value="credit">آجل</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>نوع المشتريات</label>
                    <select id="purchase_type">
                        <option value="parts">قطع</option>
                        <option value="items">اجهزة</option>
                    </select>
                </div>
            </div>

            {{-- جدول المشتريات --}}
            <div class="parts-section">
                <h3 id="section-title">القطع  </h3>
                <table class="parts-table">
                    <thead>
                    <tr>
                        <th>الصنف/القطعة</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>الإجمالي</th>
                        <th>إضافة/حذف</th>
                    </tr>
                    </thead>
                    <tbody id="purchase-table">
                    <tr class="purchase-row">
                        <td class="td-select">
                            <select name="parts[0][part_id]" class="part-select">
                                <option value="">اختر القطعة</option>
                                @foreach($parts as $part)
                                    <option value="{{ $part->id }}" data-price="{{ $part->purchase_price }}">{{ $part->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="parts[0][quantity]" value="1" class="qty-input" min="1">
                        </td>
                        <td>
                            <input type="number" name="parts[0][purchase_price]" value="0" class="price-input">
                        </td>
                        <td>
                            <input type="number" name="parts[0][total_price]" value="0" class="total-input" readonly>
                        </td>
                        <td>
                            <button type="button" id="add-row" class="btn btn-primary">+</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            {{-- ملخص الفاتورة --}}
            <div class="row-3">
                <div class="form-group">
                    <label>إجمالي الفاتورة</label>
                    <input type="number" name="total_amount" id="total_amount" value="0" readonly>
                </div>

                <div class="form-group">
                    <label>المدفوع</label>
                    <input type="number" name="paid_amount" id="paid_amount" value="0">
                </div>

                <div class="form-group">
                    <label>المتبقي</label>
                    <input type="number" name="remaining_amount" id="remaining_amount" value="0" readonly>
                </div>
            </div>

            {{-- أزرار --}}
            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="btn-primary">حفظ الفاتورة</button>
            </div>

        </form>
    </div>

    <script>
        window.parts = @json($parts);
        window.items = @json($items ?? []);
        window.itemUnits = @json($itemUnits ?? []);
    </script>

    <script src="{{ asset('js/purchase.js') }}"></script>
</x-app-layout>
