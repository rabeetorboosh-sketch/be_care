<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">الأجهزة</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="btn btn-primary">
            <a href="{{ route('devices.create') }}">
                إضافة <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>كود الجهاز</th>
                        <th>نوع الجهاز</th>
                        <th>العميل</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($devices as $device)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $device->device_code }}</td>
                            <td>{{ $device->type->name ?? '-' }} - {{ $device->type->brand ?? '-' }}</td>
                            <td>{{ $device->customer->name ?? '-' }}</td>
                            <td>{{ $device->status }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('devices.show', $device->id) }}" class="btn btn-info">عرض</a>
                                    <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-warning">تعديل</a>

                                    <form action="{{ route('devices.destroy', $device->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="if(confirm('هل أنت متأكد؟')) this.form.submit();">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">لا توجد أجهزة حالياً</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
