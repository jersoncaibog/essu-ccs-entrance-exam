<?php
include 'classes/connection.php';

if (isset($_GET['lrn'])) {
    $lrn = $conn->real_escape_string($_GET['lrn']);
    
    $sql = "SELECT id FROM student WHERE lrn = '$lrn'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'student_id' => $row['id']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Student not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'LRN not provided'
    ]);
}

$conn->close();
?> 