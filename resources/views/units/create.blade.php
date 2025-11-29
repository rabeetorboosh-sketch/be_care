<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضافة وحدة جديدة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('units.store') }}" method="POST">
            @csrf

            <div class="row-2">

                <div class="form-group">
                    <label>اسم الوحدة</label>
                    <input name="name" type="text" value="{{ old('name') }}" autocomplete="off">
                    @error('name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>


            </div>

            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="btn-primary">حفظ</button>
            </div>

        </form>
    </div>
</x-app-layout>
