<?php
// Required vars before including: $pageTitle, $activePage
$base = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> – Admin Panel</title>
    <link rel="stylesheet" href="<?= $base ?>/assets/admin-dashboard1.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/admin-dashboard-modal.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/admin-account.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="<?= $base ?>/images/IT.png" alt="Logo">
        <h2>Admin Panel</h2>
    </div>
    <ul>
        <li class="<?= $activePage === 'questions' ? 'active' : '' ?>">
            <a href="<?= $base ?>/admin/questions">
                <i class="fas fa-question-circle"></i> Manage Questions
            </a>
        </li>
        <li class="<?= $activePage === 'students' ? 'active' : '' ?>">
            <a href="<?= $base ?>/admin/students">
                <i class="fas fa-users"></i> Student Records
            </a>
        </li>
        <li class="<?= $activePage === 'account' ? 'active' : '' ?>">
            <a href="<?= $base ?>/admin/account">
                <i class="fas fa-user-cog"></i> Account
            </a>
        </li>
        <li>
            <a href="<?= $base ?>/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
