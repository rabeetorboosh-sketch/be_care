document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector(".toggle-btn");
    const sidebar = document.querySelector(".sidebar");

    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("show");
        });
    }

    // التعامل مع القوائم الفرعية
    const dropdownBtns = document.querySelectorAll(".dropdown-btn");

    dropdownBtns.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation(); // عشان ما ياثر على الاب

            const menuItem = this.parentElement;
            const siblings = menuItem.parentElement.querySelectorAll(".menu-item");

            // اغلق بس الإخوة بنفس المستوى


            // فتح/إغلاق الحالي
            menuItem.classList.toggle("active");
        });
    });
});



document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    if (window.innerWidth > 768) {
        sidebar.classList.add("show");
    } else {
        sidebar.classList.remove("show");
    }
});
