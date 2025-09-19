<?php

include 'classes/connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $conn->real_escape_string($_POST['id']);

        $sql = "DELETE FROM admin_quiz WHERE ID = '$id'";

        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    $conn->close();

?>