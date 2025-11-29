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
            <div class="row-3">
                <div class="form-group">
                    <label>تاريخ الفاتورة</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date) }}">
                </div>

                <div class="form-group">
                    <label>اختر المورد</label>
                    <select name="supplier_id" id="supplier_id">
                        <option value="0" {{ $invoice->supplier_id == 0 ? 'selected' : '' }}>مورد نقدي</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $invoice->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>اختر المخزن</label>
                    <select name="warehouse_id" id="warehouse_id">
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $invoice->warehouse_id == $warehouse->id ? 'selected' : ($warehouse->is_main ? 'selected' : '') }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>الصندوق</label>
                    <select name="cash_box_id" required>
                        @foreach($cashBoxes as $box)
                            <option value="{{ $box->id }}" @selected($invoice->cash_box_id == $box->id || $box->is_main == 1)>
                                {{ $box->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>نوع الدفع</label>
                    <select name="payment_type" id="payment_type">
                        <option value="cash" {{ $invoice->payment_status == 'cash' ? 'selected' : '' }}>نقدي</option>
                        <option value="credit" {{ $invoice->payment_status == 'credit' ? 'selected' : '' }}>آجل</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>نوع المشتريات</label>
                    <select id="purchase_type">
                        <option value="parts" {{ $invoice->items->count() == 0 ? 'selected' : '' }}>قطع</option>
                        <option value="items" {{ $invoice->items->count() > 0 ? 'selected' : '' }}>اجهزة</option>
                    </select>
                </div>
            </div>

            {{-- جدول المشتريات --}}
            <div class="parts-section">
                <h3 id="section-title">{{ $invoice->items->count() > 0 ? 'الأجهزة' : 'القطع' }}</h3>
                <table class="parts-table">
                    <thead>
                    <tr>
                        <th>الصنف/القطعة</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>الإجمالي</th>
                        <th>إضافة/حذف</th>
                    </tr>
                    </thead>
                    <tbody id="purchase-table">
                    @php $inc = 0; @endphp
                    @if($invoice->parts->count() > 0)
                        @foreach($invoice->parts as $part)
                            <tr class="purchase-row">
                                <td class="td-select">
                                    <select name="parts[{{ $inc }}][part_id]" class="part-select">
                                        <option value="">اختر القطعة</option>
                                        @foreach($parts as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->purchase_price }}"
                                                {{ $part->part_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td></td>
                                <td><input type="number" name="parts[{{ $inc }}][quantity]" value="{{ $part->quantity }}" class="qty-input" min="1"></td>
                                <td><input type="number" name="parts[{{ $inc }}][purchase_price]" value="{{ $part->purchase_price }}" class="price-input"></td>
                                <td><input type="number" name="parts[{{ $inc }}][total_price]" value="{{ $part->total_price }}" class="total-input" readonly></td>
                                <td><button type="button" id="add-row" class="btn btn-primary">+</button></td>
                            </tr>
                            @php $inc++; @endphp
                        @endforeach
                    @elseif($invoice->items->count() > 0)
                        @foreach($invoice->items as $item)
                            <tr class="purchase-row">
                                <td class="td-select">
                                    <select name="items[{{ $inc }}][item_id]" class="item-select">
                                        <option value="">اختر الصنف</option>
                                        @foreach($items as $itm)
                                            <option value="{{ $itm->id }}" data-price="{{ $itm->unit_price }}" {{ $item->item_id == $itm->id ? 'selected' : '' }}>
                                                {{ $itm->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="items[{{ $inc }}][unit_id]" class="unit-select">
                                        <option value="">اختر الوحدة</option>
                                        @foreach($itemUnits as $u)
                                            @if($u->item_id == $item->item_id)
                                                <option value="{{ $u->id }}" {{ $item->unit_id == $u->id ? 'selected' : '' }}>
                                                    {{ $u->unit->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[{{ $inc }}][quantity]" value="{{ $item->quantity }}" class="qty-input" min="1"></td>
                                <td><input type="number" name="items[{{ $inc }}][unit_price]" value="{{ $item->unit_price }}" class="price-input"></td>
                                <td><input type="number" name="items[{{ $inc }}][total_price]" value="{{ $item->total_price }}" class="total-input" readonly></td>
                                <td><button type="button" id="add-row" class="btn btn-primary">+</button></td>
                            </tr>
                            @php $inc++; @endphp
                        @endforeach
                    @else
                        <tr class="purchase-row">
                            <td class="td-select">
                                <select name="parts[0][part_id]" class="part-select">
                                    <option value="">اختر القطعة</option>
                                    @foreach($parts as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->purchase_price }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td></td>
                            <td><input type="number" name="parts[0][quantity]" value="1" class="qty-input" min="1"></td>
                            <td><input type="number" name="parts[0][purchase_price]" value="0" class="price-input"></td>
                            <td><input type="number" name="parts[0][total_price]" value="0" class="total-input" readonly></td>
                            <td><button type="button" id="add-row" class="btn btn-primary">+</button></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            {{-- ملخص الفاتورة --}}
            <div class="row-3">
                <div class="form-group">
                    <label>إجمالي الفاتورة</label>
                    <input type="number" name="total_amount" id="total_amount" value="{{ $invoice->total_amount }}" readonly>
                </div>

                <div class="form-group">
                    <label>المدفوع</label>
                    <input type="number" name="paid_amount" id="paid_amount" value="{{ $invoice->paid_amount }}">
                </div>

                <div class="form-group">
                    <label>المتبقي</label>
                    <input type="number" name="remaining_amount" id="remaining_amount" value="{{ $invoice->remaining_amount }}" readonly>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('purchase_invoices.index') }}" class="btn btn-secondary">عودة</a>
                <button type="submit" class="btn-primary">حفظ التعديلات</button>
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
