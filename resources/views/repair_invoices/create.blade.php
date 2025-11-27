<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضــافــة فــاتـورة صـيانة
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

        <form class="smart-form" action="{{ route('repair_invoices.store') }}" method="POST">
            @csrf

            {{-- بيانات العميل والجهاز --}}
            <div class="row-2">
                <div class="form-group">
                    <label>اختر العميل</label>
                    <select name="customer_id" id="customer_id">
                        <option value="0"> عميل نقدي </option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group"  style="display: none">
                    <label>اختر الجهاز</label>
                    <select name="device_id" id="device_id">
                        <option value="">اختر الجهاز</option>
                    </select>

                </div>
                <div class="form-group">
                    <label>اختر نوع الجهاز</label>
                    <select name="device_type" id="device_type" >
                        <option value="">اختر النوع </option>
                        @foreach($device_types as $device_type)
                            <option value="{{ $device_type->id }}">{{ $device_type->name }} - {{ $device_type->brand }}</option>
                        @endforeach
                    </select>
                    <a href="{{route('device-types.create')}}" class="btn btn-primary">اضافة+</a>
                </div>
            </div>

            {{-- بيانات الفاتورة --}}
            <div class="row-2">

                <div class="form-group">
                    <label>تاريخ الفاتورة</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status">
                        <option value="ready">جاهز</option>
                        <option value="in_progress">جاري الصيانة</option>
                        <option value="delivered">تم التسليم</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>نوع الدفع</label>
                    <select name="payment_type">
                        <option value="cash">نقدي</option>
                        <option value="credit">آجل</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>اختر الصندوق </label>
                    <select name="cash_box_id" id="cash_box_id">

                        @foreach($boxes as $box)
                            <option value="{{ $box->id }}" {{$box->is_main==1 ?'selected':''}} >{{ $box->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


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
                    <tr>
                        <td  class="td-select">
                            <select name="parts[0][part_id]" class="part-select" >
                                <option value="">اختر القطعة</option>
                                @foreach($parts as $part)
                                    <option value="{{ $part->id }}" data-price="{{ $part->selling_price }}">{{ $part->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="parts[0][qty]" value="1" class="qty-input" min="1">
                        </td>
                        <td>
                            <input type="number" name="parts[0][unit_price]" value="0" class="price-input">
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
            <div class="row-2">
                <div class="form-group">
                    <label>إجمالي قطع الغيار</label>
                    <input name="total_parts_price" type="number" id="total_parts_price" value="0" readonly>
                </div>

                <div class="form-group">
                    <label>أجرة الخدمة</label>
                    <input type="number" id="service_fee" name="service_fee" value="0">
                </div>

                <div class="form-group">
                    <label>إجمالي الفاتورة</label>
                    <input name="total_amount" type="number" id="total_amount" value="0" readonly>
                </div>

                <div class="form-group">
                    <label>المدفوع</label>
                    <input name="paid_amount" type="number" id="paid_amount"  value="0">
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
    </script>
    <script src="{{ asset('js/repair_invoice.js') }}"></script>
</x-app-layout>
