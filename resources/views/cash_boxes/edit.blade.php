<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل صندوق نقدي
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('cash_boxes.update', $cashBox->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>اسم الصندوق</label>
                    <input name="name" type="text" value="{{ old('name', $cashBox->name) }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="is_active">
                        <option value="1" @if($cashBox->is_active) selected @endif>نشط</option>
                        <option value="0" @if(!$cashBox->is_active) selected @endif>غير نشط</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>الصندوق الرئيسي</label>
                    <select name="is_main">
                        <option value="1" @if($cashBox->is_main) selected @endif>نعم</option>
                        <option value="0" @if(!$cashBox->is_main) selected @endif>لا</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('cash_boxes.index') }}" class="btn btn-secondary">رجوع</a>
                <button type="submit" class="btn-primary">تحديث</button>
            </div>
        </form>
    </div>
</x-app-layout>
