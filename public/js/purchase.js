document.addEventListener("DOMContentLoaded", function () {



    const purchaseTypeSelect = document.getElementById('purchase_type');
    const tableBody = document.getElementById('purchase-table');
    const sectionTitle = document.getElementById('section-title');
    const totalAmountInput = document.getElementById("total_amount");
    const paidInput = document.getElementById("paid_amount");
    const remainingInput = document.getElementById("remaining_amount");
    const paymentTypeSelect = document.getElementById("payment_type");
    const supplierSelect = document.getElementById("supplier_id");

    let inc = 0;

    function createRow(type, index) {
        if (type === 'parts') {
            return `<tr class="purchase-row">
                        <td class="td-select">
                            <select name="parts[${index}][part_id]" class="w-full part-select border-gray-300 rounded">
                                <option value="">اختر القطعة</option>
                                ${window.parts.map(p => `<option value="${p.id}" data-price="${p.purchase_price}">${p.name}</option>`).join('')}
                            </select>
                        </td>
                        <td><input type="number" name="parts[${index}][quantity]" value="1" class="w-full qty-input border-gray-300 rounded" min="1"></td>
                        <td><input type="number" name="parts[${index}][purchase_price]" value="0" class="w-full price-input border-gray-300 rounded"></td>
                        <td><input type="number" name="parts[${index}][total_price]" value="0" class="w-full total-input border-gray-300 rounded" readonly></td>
                        <td><button type="button" class="btn-remove px-2 py-1 bg-red-600 text-white rounded">X</button></td>
                    </tr>`;
        } else {
            return `<tr class="purchase-row">
                    <td class="td-select">
                        <select name="items[${index}][item_id]" class="w-full item-select border-gray-300 rounded">
                            <option value="">اختر الصنف</option>
                            ${window.items.map(i => `<option value="${i.id}" data-price="${i.unit_price || 0}">${i.name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                    <select name="items[${index}][unit_id]" class="w-full unit-select border-gray-300 rounded">
                           
                        </select>
                        </td>
                    <td>

                        <input type="number" name="items[${index}][quantity]" value="1" class="w-full qty-input border-gray-300 rounded" min="1">
                    </td>
                    <td><input type="number" name="items[${index}][unit_price]" value="0" class="w-full price-input border-gray-300 rounded"></td>
                    <td><input type="number" name="items[${index}][total_price]" value="0" class="w-full total-input border-gray-300 rounded" readonly></td>
                    <td><button type="button" class="btn-remove px-2 py-1 bg-red-600 text-white rounded">X</button></td>
                </tr>`;
        }
    }
    function createFirstRow(type, index) {
        if (type === 'parts') {
            return `<tr class="purchase-row">
                        <td class="td-select">
                            <select name="parts[${index}][part_id]" class="w-full part-select border-gray-300 rounded">
                                <option value="">اختر القطعة</option>
                                ${window.parts.map(p => `<option value="${p.id}" data-price="${p.purchase_price}">${p.name}</option>`).join('')}
                            </select>
                        </td>
                        <td><input type="number" name="parts[${index}][quantity]" value="1" class="w-full qty-input border-gray-300 rounded" min="1"></td>
                        <td><input type="number" name="parts[${index}][purchase_price]" value="0" class="w-full price-input border-gray-300 rounded"></td>
                        <td><input type="number" name="parts[${index}][total_price]" value="0" class="w-full total-input border-gray-300 rounded" readonly></td>
                       <td> <button type="button" id="add-row" class="btn btn-primary">+</button> </td>
                    </tr>`;
        } else {
            return `<tr class="purchase-row">
                    <td class="td-select">
                        <select name="items[${index}][item_id]" class="w-full item-select border-gray-300 rounded">
                            <option value="">اختر الصنف</option>
                            ${window.items.map(i => `<option value="${i.id}" data-price="${i.unit_price || 0}">${i.name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                    <select name="items[${index}][unit_id]" class="w-full unit-select border-gray-300 rounded">

                        </select>
                        </td>
                    <td>

                        <input type="number" name="items[${index}][quantity]" value="1" class="w-full qty-input border-gray-300 rounded" min="1">
                    </td>
                    <td><input type="number" name="items[${index}][unit_price]" value="0" class="w-full price-input border-gray-300 rounded"></td>
                    <td><input type="number" name="items[${index}][total_price]" value="0" class="w-full total-input border-gray-300 rounded" readonly></td>
                      <td> <button type="button" id="add-row" class="btn btn-primary">+</button> </td>
                </tr>`;
        }
    }

    function updateEvents() {
        document.querySelectorAll(".part-select, .item-select").forEach(select => {
            select.onchange = function () {
                const price = parseFloat(this.selectedOptions[0]?.dataset.price || 0);
                const row = this.closest("tr");
                row.querySelector(".price-input").value = price;
                calculateTotals();
            }
        });

        document.querySelectorAll(".qty-input, .price-input").forEach(input => {
            input.oninput = function () {
                calculateRowTotal(this.closest("tr"));
            }
        });

        document.querySelectorAll(".btn-remove").forEach(btn => {
            btn.onclick = function () {
                this.closest("tr").remove();
                calculateTotals();
            }
        });


        document.querySelectorAll(".item-select").forEach(select => {
            select.onchange = function () {
                const row = this.closest("tr");
                const itemId = parseInt(this.value);
                const unitSelect = row.querySelector(".unit-select");

                window.itemUnits
                    .filter(u => u.item_id === itemId)
                    .forEach(u => {

                        const option = document.createElement('option');
                        option.value = u.id;
                      option.textContent = u.unit.name; // تأكد من أن unit_name موجودة
                        unitSelect.appendChild(option);
                    });

                // تعيين سعر الوحدة
                const price = parseFloat(this.selectedOptions[0]?.dataset.price || 0);
                row.querySelector(".price-input").value = price;

                calculateTotals();
            };
        });

    }

    function calculateRowTotal(row) {
        const qty = parseFloat(row.querySelector(".qty-input").value) || 0;
        const price = parseFloat(row.querySelector(".price-input").value) || 0;
        row.querySelector(".total-input").value = (qty * price).toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let total = 0;
        document.querySelectorAll(".total-input").forEach(i => total += parseFloat(i.value) || 0);
        totalAmountInput.value = total.toFixed(2);

        if (paymentTypeSelect.value === "cash") {
            paidInput.value = total.toFixed(2);
            remainingInput.value = 0;
            paidInput.readOnly = true;
        } else {
            paidInput.readOnly = false;
            remainingInput.value = (total - (parseFloat(paidInput.value) || 0)).toFixed(2);
        }
    }

    function updatePaymentFields() {
        const paymentType = paymentTypeSelect.value;
        const totalAmount = parseFloat(totalAmountInput.value) || 0;

        if (paymentType === "cash") {
            paidInput.value = totalAmount;
            remainingInput.value = 0;
            paidInput.readOnly = true;
        } else {
            paidInput.readOnly = false;
            remainingInput.value = (totalAmount - (parseFloat(paidInput.value) || 0)).toFixed(2);
        }
    }

    // منع الدفع الآجل إذا المورد غير محدد أو قيمته صفر
    paymentTypeSelect.addEventListener("change", function () {
        const supplierId = supplierSelect.value;
        if (this.value === "credit" && (!supplierId || supplierId == "0")) {
            alert("للدفع الآجل يجب اختيار مورد صالح");
            this.value = "cash"; // إعادة النوع إلى نقدي تلقائياً
            supplierSelect.focus();
        }
        updatePaymentFields();
    });

    paidInput.addEventListener("input", updatePaymentFields);
    totalAmountInput.addEventListener("input", updatePaymentFields);

    purchaseTypeSelect.addEventListener('change', function() {
        const type = this.value;
        tableBody.innerHTML = '';
        inc = 0;
        if(type === 'parts') {
            sectionTitle.textContent = 'القطع  ';
        } else {
            sectionTitle.textContent = 'الاجهزة  ';
        }

        const thead = tableBody.closest('table').querySelector('thead tr');
        if(type === 'items') {
            thead.innerHTML = `
            <th>الصنف/الجهاز</th>
            <th>الوحدة</th>
            <th>الكمية</th>
            <th>سعر الشراء</th>
            <th>الإجمالي</th>
            <th>إضافة/حذف</th>
        `;
        } else {
            thead.innerHTML = `
            <th>الصنف/القطعة</th>
            <th>الكمية</th>
            <th>سعر الشراء</th>
            <th>الإجمالي</th>
            <th>إضافة/حذف</th>
        `;
        }

        tableBody.innerHTML = createFirstRow(type, inc);
        updateEvents();
    });

    // إضافة صف جديد
    document.body.addEventListener('click', function(e){
        if(e.target && e.target.id === 'add-row') {
            inc++;
            const type = purchaseTypeSelect.value;
            const row = document.createElement("tr");
            row.innerHTML = createRow(type, inc).replace('<tr class="purchase-row">','').replace('</tr>','');
            tableBody.appendChild(row);
            updateEvents();
        }
    });

    // تشغيل الأحداث عند البداية
    updateEvents();
    calculateTotals();
    updatePaymentFields();

});
