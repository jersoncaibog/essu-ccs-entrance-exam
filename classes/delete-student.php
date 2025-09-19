<?php
include "classes/connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM USER WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Student deleted successfully!'); window.location.href='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting student.'); window.location.href='admin-dashboard.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
