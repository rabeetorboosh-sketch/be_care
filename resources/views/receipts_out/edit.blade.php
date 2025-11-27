<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل سند صرف رقم #{{ $receipt->id }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">

        @if($errors->any())
            <div class="alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('receipts_out.update', $receipt->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">

                {{-- النوع --}}
                <div class="form-group">
                    <label>نوع السند</label>
                    <select disabled class="readonly">
                        <option>صرف</option>
                    </select>
                </div>

                {{-- نوع الحساب --}}
                <div class="form-group">
                    <label>نوع الحساب</label>
                    <select name="accountable_type" id="accountable_type" required>
                        <option value="App\Models\Customer"
                            @selected($receipt->accountable_type==='App\Models\Customer')>
                            عميل
                        </option>

                        <option value="App\Models\Supplier"
                            @selected($receipt->accountable_type==='App\Models\Supplier')>
                            مورد
                        </option>

                        <option value="App\Models\CashBox"
                            @selected($receipt->accountable_type==='App\Models\CashBox')>
                            صندوق
                        </option>
                    </select>
                </div>

                {{-- الجهة --}}
                <div class="form-group">
                    <label>إلى حساب</label>
                    <select id="accountable_select" name="accountable_id" required>

                        {{-- لو كان عميل --}}
                        @if($receipt->accountable_type === 'App\Models\Customer')
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}"
                                    @selected($receipt->accountable_id == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        @endif

                        {{-- لو كان مورد --}}
                        @if($receipt->accountable_type === 'App\Models\Supplier')
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}"
                                    @selected($receipt->accountable_id == $s->id)>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        @endif

                        {{-- لو كان صندوق --}}
                        @if($receipt->accountable_type === 'App\Models\CashBox')
                            @foreach($cashBoxes as $box)
                                <option value="{{ $box->id }}"
                                    @selected($receipt->accountable_id == $box->id)>
                                    {{ $box->name }}
                                </option>
                            @endforeach
                        @endif

                    </select>
                </div>

                {{-- الصندوق --}}
                <div class="form-group">
                    <label>الصندوق</label>
                    <select name="cash_box_id" required>
                        @foreach($cashBoxes as $box)
                            <option value="{{ $box->id }}"
                                @selected($receipt->cash_box_id == $box->id)>
                                {{ $box->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- المبلغ --}}
                <div class="form-group">
                    <label>المبلغ</label>
                    <input type="text" name="amount" value="{{ $receipt->amount }}" required>
                </div>

                {{-- الوصف --}}
                <div class="form-group">
                    <label>الوصف</label>
                    <input type="text" name="description" value="{{ $receipt->description }}">
                </div>

            </div>

            <div class="actions">
                <a href="{{ route('receipts_out.index') }}" class="btn btn-secondary">رجوع</a>
                <button type="submit" class="btn-primary">تحديث</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/receipt.js') }}"></script>
</x-app-layout>
