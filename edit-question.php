<?php
include 'classes/connection.php';

if (!isset($_GET['id'])) {
    die("No question ID provided.");
}

$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM admin_quiz WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Question not found.");
}

$row = $result->fetch_assoc(); // Fetch the question data

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $conn->real_escape_string($_POST['question']);
    $option1 = $conn->real_escape_string($_POST['option1']);
    $option2 = $conn->real_escape_string($_POST['option2']);
    $option3 = $conn->real_escape_string($_POST['option3']);
    $option4 = $conn->real_escape_string($_POST['option4']);

    // getting the actual text for the answer
    $selected_answer = $_POST['answer'];
    $answer = $$selected_answer;
    $answer = $conn->real_escape_string($answer);

    $updateSql = "UPDATE admin_quiz SET 
                    question = '$question',
                    option1 = '$option1',
                    option2 = '$option2',
                    option3 = '$option3',
                    option4 = '$option4',
                    answer = '$answer'
                  WHERE id = '$id'";

    if ($conn->query($updateSql) === TRUE) {
        echo "<script>alert('Question updated successfully!'); window.location.href='admin-dashboard.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="assets/edit-dashboard-modal.css">
</head>
<body>

    <section id="edit-question-form" class="card">
        <h2><i class="fas fa-edit"></i> Edit Question</h2>
        <form action="edit-question.php?id=<?php echo $id; ?>" method="POST">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($row['question']); ?>" required>

            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" value="<?php echo htmlspecialchars($row['option1']); ?>" required>

            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" value="<?php echo htmlspecialchars($row['option2']); ?>" required>

            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" value="<?php echo htmlspecialchars($row['option3']); ?>" required>

            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" value="<?php echo htmlspecialchars($row['option4']); ?>" required>

            <label for="answer">Correct Answer:</label>
            <select id="answer" name="answer" required>
                <option value="option1" <?php echo ($row['answer'] == $row['option1']) ? 'selected' : ''; ?>>Option 1</option>
                <option value="option2" <?php echo ($row['answer'] == $row['option2']) ? 'selected' : ''; ?>>Option 2</option>
                <option value="option3" <?php echo ($row['answer'] == $row['option3']) ? 'selected' : ''; ?>>Option 3</option>
                <option value="option4" <?php echo ($row['answer'] == $row['option4']) ? 'selected' : ''; ?>>Option 4</option>
            </select>

            <div class="form-buttons">
                <button type="submit">Update</button>
                <a href="admin-dashboard.php"><button type="button">Cancel</button></a>
            </div>
        </form>
    </section>

</body>
</html>
