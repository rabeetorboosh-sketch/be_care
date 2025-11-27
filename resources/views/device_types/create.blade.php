<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة نوع جهاز</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="smart-form" action="{{ route('device-types.store') }}" method="POST">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" value="{{ old('name') }}" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>العلامة التجارية</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" autocomplete="off">
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

