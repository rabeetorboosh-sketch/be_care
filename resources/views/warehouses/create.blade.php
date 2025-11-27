<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة مخزن جديد</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('warehouses.store') }}" method="POST">
            @csrf

            <div class="row-2">

                <div class="form-group">
                    <label>اسم المخزن</label>
                    <input type="text" name="name" value="{{ old('name') }}" autocomplete="off" required>
                    @error('name')<span class="text-red-600">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>الموقع</label>
                    <input type="text" name="location" value="{{ old('location') }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="is_active" required>
                        <option value="1" >مفعل</option>
                        <option value="0"  >معطل</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>المخزن الرئيسي</label>
                    <select name="is_main" required>
                        <option value="1" @selected(old('is_main') == 1)>نعم</option>
                        <option value="0" @selected(old('is_main', 0) == 0)>لا</option>
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
