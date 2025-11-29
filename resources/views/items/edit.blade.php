<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل صنف: {{ $item->name }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">

        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- بيانات الصنف --}}
            <div class="row-2">
                <div class="form-group">
                    <label>اسم الصنف</label>
                    <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
                </div>

                <div class="form-group">
                    <label>الشركة</label>
                    <input type="text" name="company" value="{{ old('company', $item->company) }}">
                </div>

                <div class="form-group">
                    <label>الإصدار</label>
                    <input type="text" name="version" value="{{ old('version', $item->version) }}">
                </div>

                <div class="form-group">
                    <label>المواصفات</label>
                    <textarea name="specs">{{ old('specs', $item->specs) }}</textarea>
                </div>

                <div class="form-group">
                    <label>النوع</label>
                    <select name="type">
                        <option value="new" @selected(old('type', $item->type)=='new')>جديد</option>
                        <option value="used" @selected(old('type', $item->type)=='used')>مستعمل</option>
                        <option value="refurbished" @selected(old('type', $item->type)=='refurbished')>مجدد</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <input type="text" name="status" value="{{ old('status', $item->status) }}">
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{ old('notes', $item->notes) }}</textarea>
                </div>
            </div>

            {{-- جدول الوحدات --}}
            <div class="parts-section">
                <h3>الوحدات</h3>
                <table class="parts-table">
                    <thead>
                    <tr>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>إضافة / حذف</th>
                    </tr>
                    </thead>
                    <tbody id="units-table">

                    @foreach($item->units as $index => $unit)
                        <tr>
                            <td>
                                <select name="units[{{ $index }}][unit_id]" class="unit-select" required>

                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}"
                                            @selected($unit->unit_id == $u->id)>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="units[{{ $index }}][qty]" value="{{ $unit->qty }}"
                                       min="0.01" step="0.01" class="qty-input" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row">-</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <button type="button" id="add-row" class="btn btn-primary mt-2">إضافة سطر</button>
            </div>

            {{-- أزرار --}}
            <div class="actions">
                <a href="{{ route('items.index') }}" class="btn-secondary">عودة</a>
                <button type="submit" class="btn-primary">حفظ التعديلات</button>
            </div>
        </form>
    </div>

    <script>
        window.units = @json($units);
        window.lastIndex = {{ $item->units->count() }};

        let table = document.getElementById('units-table');
        let addBtn = document.getElementById('add-row');
        let index = window.lastIndex;

        addBtn.addEventListener('click', function() {
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="units[${index}][unit_id]" class="unit-select" required>

                        ${window.units.map(u => `<option value="${u.id}">${u.name}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" name="units[${index}][qty]" value="1" min="0.01" step="0.01" class="qty-input" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">-</button>
                </td>
            `;
            table.appendChild(row);
            index++;

            row.querySelector('.remove-row').addEventListener('click', function() {
                row.remove();
            });
        });

        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.addEventListener('click', function() {
                btn.closest('tr').remove();
            });
        });
    </script>
</x-app-layout>
