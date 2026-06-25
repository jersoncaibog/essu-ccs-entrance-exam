<?php
session_start();
if (!isset($_SESSION['adminLoggedIn']) || !isset($_SESSION['pending_account'])) {
    header('Location: admin/account');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin/account');
    exit();
}

include 'classes/connection.php';

$adminId      = $_SESSION['adminId'] ?? 1;
$entered_otp  = trim($_POST['otp'] ?? '');
$pending      = $_SESSION['pending_account'];

// Check expiry
if (time() > $pending['otp_expiry']) {
    unset($_SESSION['pending_account'], $_SESSION['show_otp_modal'], $_SESSION['otp_email']);
    $_SESSION['account_error'] = 'OTP has expired. Please try again.';
    header('Location: admin/account');
    exit();
}

// Validate OTP
if ($entered_otp !== $pending['otp']) {
    $_SESSION['account_error'] = 'Invalid OTP code. Please try again.';
    $_SESSION['show_otp_modal'] = true;
    // Restore masked email for re-display
    $parts  = explode('@', $pending['email']);
    $_SESSION['otp_email'] = substr($parts[0], 0, 3) . '***@' . $parts[1];
    header('Location: admin/account');
    exit();
}

// Build dynamic UPDATE query
$updates = [];
$params  = [];
$types   = '';

$updates[] = 'username = ?';
$params[]  = $pending['username'];
$types    .= 's';

$updates[] = 'email = ?';
$params[]  = $pending['email'];
$types    .= 's';

if (!empty($pending['password_hash'])) {
    $updates[] = 'password = ?';
    $params[]  = $pending['password_hash'];
    $types    .= 's';
}

$params[] = $adminId;
$types   .= 'i';

$sql  = 'UPDATE admin SET ' . implode(', ', $updates) . ' WHERE id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->close();
$conn->close();

unset($_SESSION['pending_account'], $_SESSION['show_otp_modal'], $_SESSION['otp_email']);
$_SESSION['account_success'] = 'Account updated successfully!';

header('Location: admin/account');
exit();
