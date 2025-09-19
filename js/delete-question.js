// for deleting records
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let questionId = this.getAttribute("data-id");

            if (confirm("Are you sure you want to delete this question?")) {
                fetch("delete-question.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + questionId
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("Question deleted successfully!");
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
