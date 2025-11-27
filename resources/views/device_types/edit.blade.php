<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل نوع جهاز</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('device-types.update', $deviceType->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $deviceType->name) }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>العلامة التجارية</label>
                    <input type="text" name="brand" value="{{ old('brand', $deviceType->brand) }}" autocomplete="off">
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('device-types.index') }}" class="btn btn-secondary ">رجوع</a>
                <button type="submit" class="  btn-primary">تحديث</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
