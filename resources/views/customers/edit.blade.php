<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تـعـديــل عـمـيــل
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-4xl mx-auto">

            <form class="smart-form" action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row-2">

                    {{-- الاسم --}}
                    <div class="form-group">
                        <label>الاســــــــــــــــــم</label>
                        <input name="name" type="text"
                               value="{{ old('name', $customer->name) }}"
                               autocomplete="off">
                    </div>

                    {{-- الهاتف --}}
                    <div class="form-group">
                        <label>الـــــهــــاتـــــــف</label>
                        <input name="phone" type="text"
                               value="{{ old('phone', $customer->phone) }}"
                               autocomplete="off">
                    </div>

                    {{-- رقم الهوية --}}
                    <div class="form-group">
                        <label>رقـــــــــــــم الــهــويــــــة</label>
                        <input name="national_id" type="text"
                               value="{{ old('national_id', $customer->national_id) }}"
                               autocomplete="off">
                    </div>

                    {{-- الحالة --}}
                    <div class="form-group">
                        <label>الــــــحـــــــالــــــة</label>
                        <select name="is_active">
                            <option value="1" @if($customer->is_active) selected @endif>نشـــط</option>
                            <option value="0" @if(!$customer->is_active) selected @endif>غــيــر نشــط</option>
                        </select>
                    </div>

                </div>

                <div class="actions">
                    <a href="{{ route('customers.index') }}" class="btn-primary">رجوع</a>
                    <button type="submit" class="btn-save">تحديث</button>
                </div>

            </form>

        </div>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>

</x-app-layout>
