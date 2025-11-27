<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضافة صندوق نقدي
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('cash_boxes.store') }}" method="POST">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>اسم الصندوق</label>
                    <input name="name" type="text" value="{{ old('name') }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>الرصيد الافتتاحي</label>
                    <input name="opening_balance" type="number" step="0.01" value="{{ old('opening_balance', 0) }}">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="is_active">
                        <option value="1" @if(old('is_active', 1)) selected @endif>نشط</option>
                        <option value="0" @if(old('is_active') === "0") selected @endif>غير نشط</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>الصندوق الرئيسي</label>
                    <select name="is_main">
                        <option value="1" @if(old('is_main') == 1) selected @endif>نعم</option>
                        <option value="0" @if(old('is_main', 0) == 0) selected @endif>لا</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</x-app-layout>
