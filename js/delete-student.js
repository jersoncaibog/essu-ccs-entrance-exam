document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            var studentId = this.getAttribute("data-id");
            if (confirm("Are you sure you want to delete this student record?")) {
                fetch(APP_BASE + "/delete-student.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + studentId
                })
                .then(function (res) { return res.text(); })
                .then(function (data) {
                    if (data.trim() === "success") {
                        alert("Student deleted successfully!");
                        location.reload();
                    } else {
                        alert("Error deleting student.");
                    }
                })
                .catch(function (err) { console.error("Error:", err); });
            }
        });
    });
});
