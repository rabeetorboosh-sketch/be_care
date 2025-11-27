document.addEventListener("DOMContentLoaded", function () {

    // 1) عند اختيار العميل → جلب أجهزته تلقائياً
    const customerSelect = document.getElementById("customer_id");
    const deviceSelect = document.getElementById("device_id");

    customerSelect.addEventListener("change", async function () {
        const customerId = this.value;

        if(!customerId) {
            deviceSelect.innerHTML = "<option value=''>اختر الجهاز</option>";
            return;
        }

        deviceSelect.innerHTML = "<option value=''>تحميل...</option>";

        try {
            const response = await fetch(`/api/customer-devices/${customerId}`);
            if(!response.ok) throw new Error("حدث خطأ في جلب الأجهزة");

            const devices = await response.json();

            deviceSelect.innerHTML = "<option value=''>اختر الجهاز</option>";
            devices.forEach(device => {
                const option = document.createElement("option");
                option.value = device.id;
                option.textContent = `${device.device_code} - ${device.type?.name ?? ''} - ${device.type?.brand ?? ''}`;
                deviceSelect.appendChild(option);
            });
        } catch (err) {
            console.error(err);
            deviceSelect.innerHTML = "<option value=''>خطأ في التحميل</option>";
        }
    });

    // 2) إضافة صف جديد
    const addBtn = document.getElementById("add-row");
    const partsTable = document.getElementById("parts-table");
var inc =0;
    addBtn.addEventListener("click", function () {
        const row = document.createElement("tr");
inc++;
        row.innerHTML = `
            <td class="td-select">
                <select name="parts[${inc}][part_id]" class="w-full part-select border-gray-300 rounded">
                    <option value="">اختر القطعة</option>
                    ${window.parts.map(p => `<option value="${p.id}" data-price="${p.selling_price}">${p.name}</option>`).join("")}
                </select>
            </td>
            <td>
                <input type="number" name="parts[${inc}][qty]" value="1" class="w-full qty-input border-gray-300 rounded" min="1">
            </td>
            <td>
                <input type="number" name="parts[${inc}][unit_price]" value="0" class="w-full price-input border-gray-300 rounded">
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

    // 3) ربط الأحداث على الصفوف الحالية والجديدة
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

    updateEvents();

    // 4) حساب إجمالي كل صف
    function calculateRowTotal(row) {
        const qty = parseFloat(row.querySelector(".qty-input").value) || 0;
        const price = parseFloat(row.querySelector(".price-input").value) || 0;
        row.querySelector(".total-input").value = (qty * price).toFixed(2);
        calculateTotals();
    }

    // 5) حساب إجمالي الفاتورة
    const partsTotalInput = document.getElementById("total_parts_price");
    const serviceFeeInput = document.getElementById("service_fee");
    const totalAmountInput = document.getElementById("total_amount");
    const paidInput = document.getElementById("paid_amount");
    const remainingInput = document.getElementById("remaining_amount");

    serviceFeeInput.addEventListener("input", calculateTotals);
    paidInput.addEventListener("input", calculateTotals);

    function calculateTotals() {
        let partsTotal = 0;
        document.querySelectorAll(".total-input").forEach(i => {
            partsTotal += parseFloat(i.value) || 0;
        });
        partsTotalInput.value = partsTotal.toFixed(2);

        const serviceFee = parseFloat(serviceFeeInput.value) || 0;
        const invoiceTotal = partsTotal + serviceFee;
        totalAmountInput.value = invoiceTotal.toFixed(2);

        // تحديث المدفوع والمتبقي مباشرة حسب نوع الدفع
        const paymentType = paymentTypeSelect.value;
        if (paymentType === "cash") {
            paidInput.value = invoiceTotal;
            remainingInput.value = 0;
            paidInput.readOnly = true;
        } else {
            paidInput.readOnly = false;
            const paid = parseFloat(paidInput.value) || 0;
            remainingInput.value = (invoiceTotal - paid).toFixed(2);
        }
    }

});
document.addEventListener("DOMContentLoaded", function () {
    const alert = document.getElementById("success-alert");
    if(alert){
        setTimeout(() => {
            alert.style.transition = "0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 1000); // 1 second
    }
});
document.addEventListener("DOMContentLoaded", function () {

    const customerSelect = document.getElementById("customer_id");

    const deviceSelectGroup = document.querySelector('select[name="device_id"]').closest(".form-group"); // حقل الأجهزة
    const deviceTypeGroup = document.querySelector('select[name="device_type"]').closest(".form-group"); // حقل نوع الجهاز

    function toggleDeviceFields() {
        const customerId = customerSelect.value;

        if (customerId == "0") {
            // عميل نقدي → عرض نوع الجهاز فقط
            deviceTypeGroup.style.display = "block";
            deviceSelectGroup.style.display = "none";
        } else {
            // عميل عادي → عرض الأجهزة فقط
            deviceTypeGroup.style.display = "none";
            deviceSelectGroup.style.display = "block";
        }
    }

    // تشغيل عند بدء الصفحة
    toggleDeviceFields();

    // تشغيل عند تغيير العميل
    customerSelect.addEventListener("change", toggleDeviceFields);

});
const paymentTypeSelect = document.querySelector('select[name="payment_type"]');
const customerSelect = document.getElementById("customer_id");


paymentTypeSelect.addEventListener("change", function () {

    if (this.value === "credit" && customerSelect.value == "0") {
        alert("للدفع الآجل يجب اختيار عميل  ");
        customerSelect.focus();
        this.value = "cash"; // إعادة النوع إلى نقدي تلقائياً
    }
});

const partsTotalInput = document.getElementById("total_parts_price");
const serviceFeeInput = document.getElementById("service_fee");
const totalAmountInput = document.getElementById("total_amount");
const paidInput = document.getElementById("paid_amount");
const remainingInput = document.getElementById("remaining_amount");

function updatePaymentFields() {
    const paymentType = paymentTypeSelect.value;
    const totalAmount = parseFloat(totalAmountInput.value) || 0;

    if (paymentType === "cash") {
        // نقدي → المدفوع = الكل والمتبقي = 0
        paidInput.value = totalAmount;
        remainingInput.value = 0;
        paidInput.readOnly = true;
    } else {
        // آجل → يمكن تعديل المدفوع والمتبقي
        paidInput.readOnly = false;
        remainingInput.value = totalAmount - (parseFloat(paidInput.value) || 0);
    }
}

// عند تغيير نوع الدفع
paymentTypeSelect.addEventListener("change", function () {
    // دفع آجل لا يمكن للعميل النقدي
    if (this.value === "credit" && customerSelect.value == "0") {
        alert("للدفع الآجل يجب اختيار عميل غير نقدي");
        customerSelect.focus();
        this.value = "cash"; // إعادة النوع إلى نقدي تلقائياً
    }
    updatePaymentFields();
});

// إعادة حساب المتبقي عند تعديل المدفوع أو إجمالي الفاتورة
paidInput.addEventListener("input", updatePaymentFields);
serviceFeeInput.addEventListener("input", updatePaymentFields);
partsTotalInput.addEventListener("input", updatePaymentFields);

// تشغيل عند بداية الصفحة
updatePaymentFields();
customerSelect.addEventListener("change", function () {
    // إذا تم اختيار عميل نقدي
    if (this.value == "0") {
        paymentTypeSelect.value = "cash"; // تغيير طريقة الدفع إلى نقدي
    }
    calculateTotals(); // إعادة حساب المدفوع والمتبقي
});
