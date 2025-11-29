<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضافة صنف جديد
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('items.store') }}" method="POST">
            @csrf

            {{-- بيانات الصنف --}}
            <div class="row-2">
                <div class="form-group">
                    <label>اسم الصنف</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>الشركة</label>
                    <input type="text" name="company" value="{{ old('company') }}">
                </div>

                <div class="form-group">
                    <label>الإصدار</label>
                    <input type="text" name="version" value="{{ old('version') }}">
                </div>

                <div class="form-group">
                    <label>المواصفات</label>
                    <textarea name="specs">{{ old('specs') }}</textarea>
                </div>

                <div class="form-group">
                    <label>النوع</label>
                    <select name="type">
                        <option value="new" @selected(old('type')=='new')>جديد</option>
                        <option value="used" @selected(old('type')=='used')>مستعمل</option>
                        <option value="refurbished" @selected(old('type')=='refurbished')>مجدد</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <input type="text" name="status" value="{{ old('status') }}">
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- جدول وحدات الصنف --}}
            <div class="parts-section">
                <h3>الوحدات</h3>
                <table class="parts-table">
                    <thead>
                    <tr>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>إضافة/حذف</th>
                    </tr>
                    </thead>
                    <tbody id="units-table">
                    <tr>
                        <td class="td-select">
                            <select name="units[0][unit_id]" class="unit-select" required>
                                <option value="">اختر الوحدة</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="units[0][qty]" value="1" min="0.01" step="0.01" class="qty-input" required>
                        </td>
                        <td>
                            <button type="button" id="add-row" class="btn btn-primary">+</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            {{-- أزرار --}}
            <div class="actions">
                <button type="reset" class="btn-secondary">إعادة تعيين</button>
                <button type="submit" class="btn-primary">حفظ الصنف</button>
            </div>
        </form>
    </div>

    {{-- تمرير الوحدات للجافاسكريبت لإضافة الصفوف ديناميكياً --}}
    <script>
        window.units = @json($units);
    </script>
    <script>
        let table = document.getElementById('units-table');
        let addBtn = document.getElementById('add-row');
        let index = 1;

        addBtn.addEventListener('click', function() {
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="units[${index}][unit_id]" class="unit-select" required>
                        <option value="">اختر الوحدة</option>
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

        // حذف الصفوف الموجودة
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.addEventListener('click', function() {
                btn.closest('tr').remove();
            });
        });
    </script>
</x-app-layout>
