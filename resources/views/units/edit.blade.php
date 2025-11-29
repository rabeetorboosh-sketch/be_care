<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل الوحدة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('units.update', $unit->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">

                <div class="form-group">
                    <label>اسم الوحدة</label>
                    <input name="name" type="text" value="{{ old('name', $unit->name) }}" autocomplete="off">
                    @error('name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>



            </div>

            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="btn-primary">تحديث</button>
            </div>

        </form>
    </div>
</x-app-layout>
