document.getElementById('submit-exam').addEventListener('click', function () {
    const answers = [];
    const questionElements = document.querySelectorAll('.question');

    questionElements.forEach((questionElement, index) => {
        const questionIdInput = questionElement.querySelector(`input[name="question_id_${index}"]`);
        
        if (!questionIdInput) {
            console.error(`Missing hidden input for question_id_${index}`);
            return;
        }

        const questionId = questionIdInput.value;
        const selectedOption = questionElement.querySelector(`input[name="q${index}"]:checked`);
        
        if (selectedOption) {
            answers.push({
                question_id: questionId,
                selected_answer: selectedOption.value
            });
        }
    });

    if (answers.length === 0) {
        alert("You must answer at least one question before submitting.");
        return;
    }

    const data = {
        student_id: 1, 
        answers: answers
    };

    console.log("Submitting data:", JSON.stringify(data)); // Debugging

    fetch('submit-exam.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text()) // Change from .json() to .text() for debugging
    .then(result => {
        console.log("Server Response:", result); // Log raw server response

        try {
            const jsonResponse = JSON.parse(result); // Parse JSON manually
            if (jsonResponse.success) {
                alert("Exam submitted successfully!");
            } else {
                alert("Error submitting the exam.");
            }
        } catch (error) {
            console.error("Invalid JSON response:", result);
            alert("Submission failed. Check the console.");
        }
    })
    .catch(error => console.error('Fetch Error:', error));
});
