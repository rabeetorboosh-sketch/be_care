
    document.addEventListener("DOMContentLoaded", function () {

    const addBtn = document.getElementById("add-row");
    const partsTable = document.getElementById("parts-table");
    let inc = 0;

    const totalAmountInput = document.getElementById("total_amount");
    const paidInput = document.getElementById("paid_amount");
    const remainingInput = document.getElementById("remaining_amount");
    const paymentTypeSelect = document.getElementById("payment_type");

    addBtn.addEventListener("click", function () {
    inc++;
    const row = document.createElement("tr");
    row.innerHTML = `
                    <td class="td-select">
                        <select name="parts[${inc}][part_id]" class="w-full part-select border-gray-300 rounded">
                            <option value="">اختر القطعة</option>
                            ${window.parts.map(p => `<option value="${p.id}" data-price="${p.purchase_price}">${p.name}</option>`).join("")}
                        </select>
                    </td>
                    <td>
                        <input type="number" name="parts[${inc}][quantity]" value="1" class="w-full qty-input border-gray-300 rounded" min="1">
                    </td>
                    <td>
                        <input type="number" name="parts[${inc}][purchase_price]" value="0" class="w-full price-input border-gray-300 rounded">
                    </td>
                    <td>
                        <input type="number" name="parts[${inc}][total_price]" value="0" class="w-full total-input border-gray-300 rounded" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn-remove px-2 py-1 bg-red-600 text-white rounded">X</button>
                    </td>
                `;
    partsTable.appendChild(row);
    updateEvents();
});

    function updateEvents() {
    document.querySelectorAll(".part-select").forEach(select => {
    select.onchange = function () {
    const price = parseFloat(this.selectedOptions[0]?.dataset.price || 0);
    const row = this.closest("tr");
    row.querySelector(".price-input").value = price;
    calculateRowTotal(row);
}
});

    document.querySelectorAll(".qty-input").forEach(input => {
    input.oninput = function () {
    calculateRowTotal(this.closest("tr"));
}
});

    document.querySelectorAll(".price-input").forEach(input => {
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

    paymentTypeSelect.addEventListener("change", calculateTotals);
    paidInput.addEventListener("input", calculateTotals);

    updateEvents();
    calculateTotals();

    // Success alert fade
    const alert = document.getElementById("success-alert");
    if(alert){
    setTimeout(() => {
    alert.style.transition = "0.5s";
    alert.style.opacity = "0";
    setTimeout(() => alert.remove(), 500);
}, 1000);
}

});
    document.addEventListener("DOMContentLoaded", function () {
        const paymentTypeSelect = document.getElementById("payment_type");
        const supplierSelect = document.getElementById("supplier_id");
        const totalAmountInput = document.getElementById("total_amount");
        const paidInput = document.getElementById("paid_amount");
        const remainingInput = document.getElementById("remaining_amount");

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

        // إعادة حساب المتبقي عند تعديل المدفوع أو إجمالي الفاتورة
        paidInput.addEventListener("input", updatePaymentFields);
        totalAmountInput.addEventListener("input", updatePaymentFields);

        // تشغيل عند بداية الصفحة
        updatePaymentFields();
    });
