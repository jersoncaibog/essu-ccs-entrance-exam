<?php
include 'classes/connection.php';

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['answers'])) {
    echo json_encode(["success" => false, "message" => "Invalid input data"]);
    exit();
}

$student_id = $conn->real_escape_string($data['student_id']);
$answers = $data['answers'];

// Calculate score
$score = 0;
foreach ($answers as $answer) {
    $question_id = $conn->real_escape_string($answer['question_id']);
    $selected_answer = $conn->real_escape_string($answer['selected_answer']);
    
    // Get correct answer from admin_quiz table
    $sql = "SELECT answer FROM admin_quiz WHERE id = '$question_id'";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        if ($selected_answer === $row['answer']) {
            $score++;
        }
    }
}

// Get current timestamp for exam date
$current_timestamp = date('Y-m-d H:i:s');

// Update student's score and exam date
$sql = "UPDATE student SET score = '$score', exam_date = '$current_timestamp' WHERE id = '$student_id'";
if ($conn->query($sql)) {
    echo json_encode([
        "success" => true, 
        "message" => "Exam submitted successfully", 
        "score" => $score,
        "exam_date" => $current_timestamp
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Database error", "error" => $conn->error]);
}

$conn->close();
?>
