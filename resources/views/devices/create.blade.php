<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة جهاز</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('devices.store') }}" method="POST">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>نوع الجهاز</label>
                    <select name="device_type_id">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @if(old('device_type_id') == $type->id) selected @endif>
                                {{ $type->name }} - {{ $type->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>العميل</label>
                    <select name="customer_id">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @if(old('customer_id') == $customer->id) selected @endif>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>كود الجهاز</label>
                    <input type="text" name="device_code" value="{{ old('device_code') }}" autocomplete="off">
                </div>





                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="btn-primary">حفظ</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
