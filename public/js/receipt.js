document.addEventListener("DOMContentLoaded", function () {

    const alert = document.getElementById("success-alert");
    if (alert) {
        setTimeout(() => {
            alert.style.transition = "0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 1000);
    }

    const accountableSelect = document.getElementById("accountable_select");
    const accountableIdInput = document.querySelector('select[name="accountable_id"]');
    const accountableTypeInput = document.querySelector('select[name="accountable_type"]');

    if (!accountableSelect || !accountableIdInput || !accountableTypeInput) return;

    // عند تغيير نوع المحاسب له
    accountableTypeInput.addEventListener("change", function () {
        const type = this.value;
        accountableSelect.innerHTML = "<option>تحميل...</option>";

        fetch(`/receipts/accountables/${encodeURIComponent(type)}`)
            .then(res => res.json())
            .then(data => {
                accountableSelect.innerHTML = "<option value=''>اختر الجهة</option>";
                data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.id;
                    option.textContent = item.name;
                    option.dataset.type = type; // حفظ نوع الـ Morph
                    accountableSelect.appendChild(option);
                });
            });
    });

 

});
