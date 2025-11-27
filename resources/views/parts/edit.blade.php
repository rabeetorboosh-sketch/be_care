<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل قطعة</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('parts.update', $part->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $part->name) }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>سعر الشراء</label>
                    <input type="text" name="purchase_price" value="{{ old('purchase_price', $part->purchase_price) }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>سعر البيع</label>
                    <input type="text" name="selling_price" value="{{ old('selling_price', $part->selling_price) }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="is_active">
                        <option value="1" @if($part->is_active) selected @endif>نشط</option>
                        <option value="0" @if(!$part->is_active) selected @endif>غير نشط</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('parts.index') }}" class="btn btn-secondary">رجوع</a>
                <button type="submit" class=" btn-primary">تحديث</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
