<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">سندات القبض</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        <div class="btn btn-primary mb-4">
            <a href="{{ route('receipts.create') }}">
                + إضافة سند قبض
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
                        <th>الحساب</th>
                        <th>الصندوق</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($receipts as $receipt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>


                            <td>{{ $receipt->accountable?->name ?? '-' }}</td>

                            <td>{{ $receipt->cashBox->name ?? '-' }}</td>

                            <td>{{ number_format($receipt->amount, 2) }}</td>

                            <td>{{ $receipt->created_at->format('Y-m-d h:i A') }}</td>

                            <td>
                                <div class="actions">

                                    <a href="{{ route('receipts.show', $receipt->id) }}" class="btn btn-info">
                                        عرض
                                    </a>

                                    <a href="{{ route('receipts.edit', $receipt->id) }}" class="btn btn-warning">
                                        تعديل
                                    </a>

                                    <form action="{{ route('receipts.destroy', $receipt->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                                onclick="if(confirm('هل تريد حذف السند؟')) this.form.submit();">
                                            حذف
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">لا توجد سندات مسجلة</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $receipts->links() }}
        </div>

    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
