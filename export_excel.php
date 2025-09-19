<?php
require 'classes/connection.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=exam_results.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "Student Name\tEmail\tLRN\tStrand\tPhone\tScore\tExam Date\n";

$query = "SELECT * FROM student";
$results = $conn->query($query);

while ($row = $results->fetch_assoc()) {
    echo $row['name'] . "\t" . 
         $row['gmail'] . "\t" . 
         $row['lrn'] . "\t" . 
         $row['strand'] . "\t" . 
         $row['phone'] . "\t" . 
         ($row['score'] !== null ? $row['score'] : 'Not taken') . "\t" .
         ($row['exam_date'] !== null ? date('M d, Y h:i A', strtotime($row['exam_date'])) : 'Not taken') . "\n";
}

$conn->close();
?>
