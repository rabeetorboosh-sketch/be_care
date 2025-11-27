<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل جهاز</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('devices.update', $device->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>نوع الجهاز</label>
                    <select name="device_type_id">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @if($device->device_type_id == $type->id) selected @endif>
                                {{ $type->name }} - {{ $type->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>العميل</label>
                    <select name="customer_id">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @if($device->customer_id == $customer->id) selected @endif>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>كود الجهاز</label>
                    <input type="text" name="device_code" value="{{ old('device_code', $device->device_code) }}">
                </div>


                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{ old('notes', $device->notes) }}</textarea>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('devices.index') }}" class="btn-primary">رجوع</a>
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
