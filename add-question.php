<?php
include 'classes/connection.php';

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

        $sql = "INSERT INTO admin_quiz (question, option1, option2, option3, option4, answer) VALUES ('$question', '$option1', '$option2', '$option3', '$option4', '$answer')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Question added successfully!'); window.location.href='admin-dashboard.php';</script>";
        } else {
            echo "Error: ". $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
?>