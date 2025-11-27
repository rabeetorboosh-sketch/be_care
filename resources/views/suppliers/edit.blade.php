<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل مورد</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="is_active">
                        <option value="1" @if($supplier->is_active) selected @endif>نشط</option>
                        <option value="0" @if(!$supplier->is_active) selected @endif>غير نشط</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('suppliers.index') }}" class="btn b btn-secondary">رجوع</a>
                <button type="submit" class="btn-primary">تحديث</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
