<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضــافــة عـمـيـل
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="row-2">

                {{-- الاسم --}}
                <div class="form-group">
                    <label>الاســــــــــــــــــم</label>
                    <input name="name" type="text" value="{{ old('name') }}" autocomplete="off">
                </div>

                {{-- الهاتف --}}
                <div class="form-group">
                    <label>الـــــهــــاتـــــــف</label>
                    <input name="phone" type="text" value="{{ old('phone') }}" autocomplete="off">
                </div>

                {{-- رقم الهوية --}}
                <div class="form-group">
                    <label>رقـــــــــــــم الــهــويــــــة</label>
                    <input name="national_id" type="text" value="{{ old('national_id') }}" autocomplete="off">
                </div>

                {{-- الحالة --}}
                <div class="form-group">
                    <label>الــــــحـــــــالــــــة</label>
                    <select name="is_active">
                        <option value="1" @if(old('is_active') == 1) selected @endif>نشـــط</option>
                        <option value="0" @if(old('is_active') == 0) selected @endif>غــيــر نشــط</option>
                    </select>
                </div>

            </div>

            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="  btn-primary">حفظ</button>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
