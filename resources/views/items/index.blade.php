<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">الأصناف</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        <div class="btn btn-primary mb-4">
            <a href="{{ route('items.create') }}">
                + إنشاء صنف جديد
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
                        <th>اسم الصنف</th>
                        <th>الشركة</th>
                        <th>الإصدار</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الوحدات</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->company ?? '-' }}</td>
                            <td>{{ $item->version ?? '-' }}</td>
                            <td>
                                @switch($item->type)
                                    @case('new') جديد @break
                                    @case('used') مستعمل @break
                                    @case('refurbished') مجدد @break
                                    @default -
                                @endswitch
                            </td>
                            <td>{{ $item->status ?? '-' }}</td>
                            <td>
                                @if($item->units->count())
                                    @foreach($item->units as $unit)
                                        {{ $unit->unit?->name ?? '-' }} ({{ $unit->qty }})<br>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-info">عرض</a>
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning">تعديل</a>

                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                                onclick="if(confirm('هل تريد حذف الصنف؟')) this.form.submit();">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">لا توجد أصناف مسجلة</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <script src="{{ asset('js/table.js') }}"></script>

</x-app-layout>
