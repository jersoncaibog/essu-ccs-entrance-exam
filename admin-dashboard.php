<?php
// Kept for backwards compatibility — all admin pages now live under admin/
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: index.php');
} else {
    header('Location: admin/questions');
}
exit();
