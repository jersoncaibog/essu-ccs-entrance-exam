document.addEventListener("DOMContentLoaded", function () {
    const adminLoginBtn = document.getElementById("admin-login-btn");
    const adminPopup = document.getElementById("admin-popup");
    const closeBtn = document.querySelector(".close-btn");
    const studentForm = document.getElementById("student-form");
    const adminForm = document.getElementById("admin-form");
    const container = document.querySelector(".container");

    // Show admin login pop-up and hide student login
    adminLoginBtn.addEventListener("click", function () {
        adminPopup.style.display = "flex";
        container.style.display = "none";
    });

    // Close admin login pop-up and show student login again
    closeBtn.addEventListener("click", function () {
        adminPopup.style.display = "none";
        container.style.display = "block";
    });

    // Validate student phone number (must be exactly 11 digits)
    studentForm.addEventListener("submit", function (event) {
        const phoneInput = document.getElementById("student-phone");
        const phonePattern = /^[0-9]{11}$/;
        if (!phonePattern.test(phoneInput.value)) {
            alert("Please enter a valid 11-digit phone number.");
            event.preventDefault();
        }
    });

    // Admin form submits normally to PHP — no JS interception needed
});

