<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة سند صرف</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('receipts_out.store') }}" method="POST">
            @csrf

            <div class="row-2">

                <div class="form-group">
                    <label>نوع السند</label>
                    <select name="receipt_type" required>
                        <option value="out" @selected(old('receipt_type') === 'deposit')>صرف</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>  نوع الحساب  </label>
                    <select name="accountable_type" required>

                        <option value="App\Models\Customer" @selected(old('accountable_type') === 'App\Models\Customer') >عميل</option>
                        <option value="App\Models\Supplier" @selected(old('accountable_type') === 'App\Models\Supplier')>مورد</option>
                        <option value="App\Models\CashBox" @selected(old('accountable_type') === 'App\Models\CashBox')>صندوق</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>من حساب </label>
                    <select id="accountable_select" name="accountable_id" required>
                        <option value="">اختر الجهة</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" >
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>الصندوق</label>
                    <select name="cash_box_id" required>
                        @foreach($cashBoxes as $box)
                            <option value="{{ $box->id }}" @selected(old('cash_box_id') == $box->id or $box->is_main==1 )>
                                {{ $box->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المبلغ</label>
                    <input type="text" name="amount" value="{{ old('amount') }}" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label>الوصف</label>
                    <input type="text" name="description" value="{{ old('description') }}" autocomplete="off">
                </div>

            </div>

            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="btn-primary">حفظ</button>
            </div>
        </form>
    </div>


    <script src="{{ asset('js/receipt.js') }}"></script>
</x-app-layout>
