<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

include 'classes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id   = (int) $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    echo $stmt->execute() ? "success" : "error";
    $stmt->close();
}

$conn->close();
