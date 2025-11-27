<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            العملاء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        {{-- زر إضافة عميل (مع صلاحيات) --}}
           <div class="btn btn-primary">
                <a href="{{ route('customers.create') }}">
                    إضافة <i class="fa-solid fa-plus"></i>
                </a>
            </div>


        <div class="table-wrap">
            <div class="table-scroll">

                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>رقم الجوال</th>
                        <th>رقم الهوية</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td class="font-semibold">
                                {{ $customer->name }}
                            </td>

                            <td>{{ $customer->phone }}</td>

                            <td>{{ $customer->national_id }}</td>

                            <td>
                                @if($customer->is_active)
                                    <span class="status status-success">نشط</span>
                                @else
                                    <span class="status status-danger">غير نشط</span>
                                @endif
                            </td>

                            <td>
                                <div class="actions">

                                    {{-- عرض --}}
                                    <a href="{{ route('customers.show', $customer->id) }}"
                                       class="btn btn-info">
                                        عرض
                                    </a>

                                    {{-- تعديل --}}
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                           class="btn btn-warning">
                                            تعديل
                                        </a>


                                    {{-- حذف --}}
                                       <form id="delete-form-{{ $customer->id }}"
                                              action="{{ route('customers.destroy', $customer->id) }}"
                                              method="POST"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    class="btn btn-danger"
                                                    onclick="confirmDelete({{ $customer->id }})">
                                                حذف
                                            </button>
                                        </form>


                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">
                                لا يوجد عملاء حالياً
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>

    <script src="{{ asset('js/table.js') }}"></script>

</x-app-layout>
