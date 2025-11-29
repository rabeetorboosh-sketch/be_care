<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            الوحدات
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        <div class="btn btn-primary mb-4">
            <a href="{{ route('units.create') }}">
                إضافة وحدة جديدة <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الوحدة</th>
                        <th>الكمية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->qty }}</td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-warning">
                                        تعديل
                                    </a>

                                    <form action="{{ route('units.destroy', $unit->id) }}"
                                          method="POST"
                                          style="display: inline-block;"
                                          onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">
                                لا توجد وحدات حالياً
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</x-app-layout>
