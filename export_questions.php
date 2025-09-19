<?php
require 'classes/connection.php';

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=exam_questions.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Create Excel header row
echo "Question\tOption 1\tOption 2\tOption 3\tOption 4\tCorrect Answer\n";

// Get questions
$sql = "SELECT * FROM admin_quiz ORDER BY created_at DESC";
$result = $conn->query($sql);

// Output each question
while ($row = $result->fetch_assoc()) {
    echo $row['question'] . "\t" . 
         $row['option1'] . "\t" . 
         $row['option2'] . "\t" . 
         $row['option3'] . "\t" . 
         $row['option4'] . "\t" . 
         ucfirst(str_replace("option", "Option ", $row['answer'])) . "\n";
}

$conn->close();
?> 