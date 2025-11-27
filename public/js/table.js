function confirmDelete(id) {
    // رسالة تأكيد
    if (confirm('هل أنت متأكد من الحذف؟')) {
        // إرسال الفورم
        document.getElementById('delete-form-' + id).submit();
    } else {
        // يمكن إضافة تنبيه صغير إذا ألغى المستخدم
        alert('تم إلغاء الحذف');
    }
}
document.querySelectorAll('.table-title').forEach(title => {
    title.addEventListener('click', function() {
        const tableWrap = this.nextElementSibling;

        tableWrap.classList.toggle('collapsed'); // يطي الجدول أو يفتحه
        this.classList.toggle('collapsed');      // يغير اتجاه السهم
    });
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
