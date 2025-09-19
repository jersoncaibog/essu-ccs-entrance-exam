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

    // Handle admin login with session storage support
    adminForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const username = document.getElementById("admin-username").value.trim();
        const password = document.getElementById("admin-password").value.trim();
    
        // Use hardcoded credentials or stored values
        const storedUsername = localStorage.getItem("adminUsername") || "admin";
        const storedPassword = localStorage.getItem("adminPassword") || "password";
    
        if (username === storedUsername && password === storedPassword) {
            sessionStorage.setItem("adminLoggedIn", "true"); // Store in sessionStorage
            alert("Login successful! Redirecting to admin dashboard...");
            window.location.href = "admin-dashboard.php?session=true"; // Pass session param
        } else {
            alert("Invalid credentials. Please try again.");
        }
    });
    

    // Handle admin sign-up and store credentials in local storage
    const adminSignupForm = document.getElementById("admin-signup-form");
    if (adminSignupForm) {
        adminSignupForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const newUsername = document.getElementById("admin-signup-username").value.trim();
            const newPassword = document.getElementById("admin-signup-password").value.trim();

            if (newUsername && newPassword) {
                localStorage.setItem("adminUsername", newUsername);
                localStorage.setItem("adminPassword", newPassword);
                alert("Admin registered successfully! You can now log in.");
                adminPopup.style.display = "none";
                container.style.display = "block";
            } else {
                alert("Please enter a valid username and password.");
            }
        });
    }

    // Prevent unauthorized access to the admin dashboard
    if (window.location.pathname.includes("admindashboard.php")) {
        const adminLoggedIn = sessionStorage.getItem("adminLoggedIn");
        if (!adminLoggedIn) {
            alert("Unauthorized access! Please log in first.");
            window.location.href = "login.html"; // Redirect to login page
        }
    }
});

//for login side:
document.getElementById("admin-form").addEventListener("submit", function() {
    console.log("Admin login form submitted!");
});
