document.addEventListener("DOMContentLoaded", function () {
    // Prevent unauthorized access to the admin dashboard
    if (window.location.pathname.includes("admin-dashboard.php")) {
        const adminLoggedIn = sessionStorage.getItem("adminLoggedIn");
        if (!adminLoggedIn) {
            alert("Unauthorized access! Please log in first.");
            window.location.href = "index.php"; // Redirect to login page
        }
    }

    // Handle logout
    const logoutButton = document.getElementById("logout-btn");
    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            sessionStorage.removeItem("adminLoggedIn"); // Clear session
            alert("You have been logged out.");
            window.location.href = "index.php"; // Redirect to login page
        });
    }
});
