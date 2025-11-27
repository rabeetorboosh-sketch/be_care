<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة مورد</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" value="{{ old('name') }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="is_active">
                        <option value="1" @if(old('is_active') == 1) selected @endif>نشط</option>
                        <option value="0" @if(old('is_active') == 0)  @endif>غير نشط</option>
                    </select>
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
