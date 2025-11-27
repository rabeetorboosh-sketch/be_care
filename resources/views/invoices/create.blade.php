<x-app-layout>

    <x-slot name="title">إنشاء فاتورة صيانة</x-slot>

    <div class="page-header">
        <h1 class="page-title">إنشاء فاتورة صيانة</h1>
    </div>

    <form action="{{ route('repair-invoices.store') }}" method="POST">
        @csrf

        {{-- ===========================
            القسم 1 : بيانات العميل والجهاز
        ============================ --}}
        <div class="card">
            <div class="card-header">بيانات العميل والجهاز</div>
            <div class="card-body grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- اختيار العميل --}}
                <div>
                    <label>العميل</label>
                    <select name="customer_id" id="customer_id" class="form-control">
                        <option value="">اختر العميل</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- اختيار الجهاز --}}
                <div>
                    <label>الجهاز</label>
                    <select name="device_id" id="device_id" class="form-control">
                        <option value="">اختر الجهاز</option>
                        {{-- سيتم تعبئته بالـ AJAX عند اختيار العميل --}}
                    </select>
                </div>

                {{-- الحالة --}}
                <div>
                    <label>حالة الفاتورة</label>
                    <select name="status" class="form-control">
                        <option value="0">استلام</option>
                        <option value="1">جاري صيانة</option>
                        <option value="2">جاهز</option>
                        <option value="3">مُسلّمة</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- ===========================
            القسم 2 : بيانات الفاتورة
        ============================ --}}
        <div class="card mt-4">
            <div class="card-header">بيانات الفاتورة</div>

            <div class="card-body grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label>رسوم الخدمة</label>
                    <input type="number" step="0.01" name="service_fee" id="service_fee" class="form-control" value="0">
                </div>

                <div>
                    <label>إجمالي قطع الغيار</label>
                    <input type="number" step="0.01" name="total_parts_price" id="total_parts_price" class="form-control" readonly>
                </div>

                <div>
                    <label>الإجمالي الكلّي</label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" readonly>
                </div>

            </div>
        </div>

        {{-- ===========================
            القسم 3 : قطع الغيار
        ============================ --}}
        <div class="card mt-4">
            <div class="card-header flex justify-between items-center">
                <span>قطع الغيار</span>
                <button type="button" class="btn bg-[var(--main-color)] text-white" id="addPartRow">
                    + إضافة قطعة
                </button>
            </div>

            <div class="card-body">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b">
                        <th>القطعة</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>الإجمالي</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody id="partsTable">
                    {{-- يتم إضافة الصفوف ديناميكياً --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===========================
            القسم 4 : الدفع
        ============================ --}}
        <div class="card mt-4">
            <div class="card-header">الدفع</div>

            <div class="card-body grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label>حالة الدفع</label>
                    <select name="payment_status" id="payment_status" class="form-control">
                        <option value="0">غير مدفوع</option>
                        <option value="1">مدفوع</option>
                    </select>
                </div>

                <div>
                    <label>المبلغ المدفوع</label>
                    <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control" value="0">
                </div>

                <div>
                    <label>المبلغ المتبقي</label>
                    <input type="number" step="0.01" name="remaining_amount" id="remaining_amount" class="form-control" readonly>
                </div>

            </div>
        </div>

        {{-- زر الحفظ --}}
        <div class="mt-4 flex justify-end">
            <button type="submit" class="btn bg-green-600 text-white px-4 py-2 rounded">
                حفظ الفاتورة
            </button>
        </div>

    </form>

</x-app-layout>

