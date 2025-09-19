// Get elements
const editForm = document.getElementById('edit-question-form');
const overlay = document.getElementById('overlay');
const cancelEditButton = document.getElementById('cancel-edit');

// Function to open the edit modal with existing data
function openEditModal(questionData) {
    document.getElementById('edit-id').value = questionData.id;
    document.getElementById('edit-question').value = questionData.question;
    document.getElementById('edit-option1').value = questionData.option1;
    document.getElementById('edit-option2').value = questionData.option2;
    document.getElementById('edit-option3').value = questionData.option3;
    document.getElementById('edit-option4').value = questionData.option4;
    document.getElementById('edit-answer').value = questionData.answer;

    editForm.style.display = 'block';
    overlay.style.display = 'block';
}

// Function to close the edit modal
function closeEditModal() {
    editForm.style.display = 'none';
    overlay.style.display = 'none';
}

// Click event to close modal
cancelEditButton.addEventListener('click', closeEditModal);
overlay.addEventListener('click', closeEditModal);

// Example function to trigger the edit modal (Modify this to match your system)
document.querySelectorAll('.edit-button').forEach(button => {
    button.addEventListener('click', function() {
        const questionData = {
            id: this.dataset.id,
            question: this.dataset.question,
            option1: this.dataset.option1,
            option2: this.dataset.option2,
            option3: this.dataset.option3,
            option4: this.dataset.option4,
            answer: this.dataset.answer
        };
        openEditModal(questionData);
    });
});
