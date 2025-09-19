<?php
require 'classes/connection.php';

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=student_records.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Create Excel header row
echo "Name\tLRN\tStrand\tGender\tPhone\tAddress\tScore\tExam Date\n";

// Get student records
$sql = "SELECT * FROM student ORDER BY exam_date DESC";
$result = $conn->query($sql);

// Output each student record
while ($row = $result->fetch_assoc()) {
    // Concatenate name fields
    $fullName = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']);
    
    echo $fullName . "\t" . 
         $row['lrn'] . "\t" . 
         $row['strand'] . "\t" . 
         $row['gender'] . "\t" . 
         $row['phone'] . "\t" . 
         $row['address'] . "\t" . 
         ($row['score'] !== null ? $row['score'] : 'Not taken') . "\t" .
         ($row['exam_date'] !== null ? date('M d, Y h:i A', strtotime($row['exam_date'])) : 'Not taken') . "\n";
}

$conn->close();
?> 