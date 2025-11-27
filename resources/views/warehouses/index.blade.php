<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">المخازن</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        <div class="btn btn-primary mb-4">
            <a href="{{ route('warehouses.create') }}">
                + إضافة مخزن جديد
            </a>
        </div>

        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-wrap">
            <div class="table-scroll">

                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المخزن</th>
                        <th>الموقع</th>
                        <th>الحالة</th>
                        <th>رئيسي</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($warehouses as $warehouse)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ $warehouse->location ?? '-' }}</td>
                            <td>{{ $warehouse->is_active ? 'مفعل' : 'معطل' }}</td>
                            <td>{{ $warehouse->is_main ? 'نعم' : 'لا' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-warning">
                                        تعديل
                                    </a>
                                    <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                                onclick="if(confirm('هل تريد حذف المخزن؟')) this.form.submit();">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">لا توجد مخازن مسجلة</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>


    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
