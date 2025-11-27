<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تــعـديــل فــاتـورة صـيانة
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

        <form class="smart-form" action="{{ route('repair_invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- بيانات العميل والجهاز --}}
            <div class="row-2">
                <div class="form-group">
                    <label>اختر العميل</label>
                    <select name="customer_id" id="customer_id">
                        <option value="0" {{ $invoice->customer_id == 0 ? 'selected' : '' }}>عميل نقدي</option>

                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="display:none">
                    <label>اختر الجهاز</label>
                    <select name="device_id" id="device_id">
                        <option value="">اختر الجهاز</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>اختر نوع الجهاز</label>
                    <select name="device_type" id="device_type">
                        <option value="">اختر النوع</option>

                        @foreach($device_types as $type)
                            <option value="{{ $type->id }}" {{ $invoice->device_type == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} - {{ $type->brand }}
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('device-types.create') }}" class="btn btn-primary">اضافة+</a>
                </div>
            </div>

            {{-- بيانات الفاتورة --}}
            <div class="row-2">

                <div class="form-group">
                    <label>تاريخ الفاتورة</label>
                    <input type="date" name="invoice_date" value="{{ $invoice->date }}">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status">
                        <option value="ready" {{ $invoice->status == 'ready' ? 'selected' : '' }}>جاهز</option>
                        <option value="in_progress" {{ $invoice->status == 'in_progress' ? 'selected' : '' }}>جاري الصيانة</option>
                        <option value="delivered" {{ $invoice->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
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
                    <label>اختر الصندوق</label>
                    <select name="cash_box_id" id="cash_box_id">
                        @foreach($boxes as $box)
                            <option value="{{ $box->id }}" {{ $invoice->cash_box_id == $box->id ? 'selected' : '' }}>
                                {{ $box->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- قطع الغيار --}}
            <div class="parts-section">
                <h3>قطع الغيار</h3>

                <table class="parts-table">
                    <thead>
                    <tr>
                        <th>القطعة</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                        <th>إضافة/حذف</th>
                    </tr>
                    </thead>

                    <tbody id="parts-table">
                    @foreach ($invoice->parts as $index => $part)
                        <tr>
                            <td class="td-select">
                                <select name="parts[{{ $index }}][part_id]" class="part-select">
                                    <option value="">اختر القطعة</option>

                                    @foreach($parts as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}"
                                            {{ $part->part_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td><input type="number" name="parts[{{ $index }}][qty]" class="qty-input" value="{{ $part->qty }}"></td>

                            <td><input type="number" name="parts[{{ $index }}][unit_price]" class="price-input" value="{{ $part->unit_price }}"></td>

                            <td><input type="number" name="parts[{{ $index }}][total_price]" class="total-input" value="{{ $part->total_price }}" readonly></td>

                            <td><button type="button" class="btn btn-danger remove-row">-</button></td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

                <button type="button" id="add-row" class="btn btn-primary mt-3">إضافة صف</button>
            </div>

            {{-- ملخص الفاتورة --}}
            <div class="row-2">
                <div class="form-group">
                    <label>إجمالي قطع الغيار</label>
                    <input name="total_parts_price" type="number" id="total_parts_price"
                           value="{{ $invoice->total_parts_price }}" readonly>
                </div>

                <div class="form-group">
                    <label>أجرة الخدمة</label>
                    <input type="number" id="service_fee" name="service_fee" value="{{ $invoice->service_fee }}">
                </div>

                <div class="form-group">
                    <label>إجمالي الفاتورة</label>
                    <input name="total_amount" type="number" id="total_amount"
                           value="{{ $invoice->total_amount }}" readonly>
                </div>

                <div class="form-group">
                    <label>المدفوع</label>
                    <input name="paid_amount" type="number" id="paid_amount"
                           value="{{ $invoice->paid_amount }}">
                </div>

                <div class="form-group">
                    <label>المتبقي</label>
                    <input type="number" name="remaining_amount" id="remaining_amount"
                           value="{{ $invoice->remaining_amount }}" readonly>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">تحديث الفاتورة</button>
            </div>

        </form>
    </div>

    <script>
        window.parts = @json($parts);
    </script>

    <script src="{{ asset('js/repair_invoice.js') }}"></script>

</x-app-layout>
