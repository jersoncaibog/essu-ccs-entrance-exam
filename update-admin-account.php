<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin/account');
    exit();
}

include 'classes/connection.php';

$adminId        = $_SESSION['adminId'] ?? 1;
$new_username   = trim($_POST['new_username'] ?? '');
$new_email      = trim($_POST['new_email'] ?? '');
$new_password   = $_POST['new_password'] ?? '';
$confirm_pass   = $_POST['confirm_password'] ?? '';
$current_pass   = $_POST['current_password'] ?? '';

// Fetch current admin record
$stmt = $conn->prepare("SELECT username, password, email FROM admin WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$admin) {
    $_SESSION['account_error'] = 'Admin account not found.';
    header('Location: admin/account');
    exit();
}

// Validate current password
if (!password_verify($current_pass, $admin['password'])) {
    $_SESSION['account_error'] = 'Current password is incorrect.';
    header('Location: admin/account');
    exit();
}

// Validate new password match
if (!empty($new_password) && $new_password !== $confirm_pass) {
    $_SESSION['account_error'] = 'New passwords do not match.';
    header('Location: admin/account');
    exit();
}

// Username required
if (empty($new_username)) {
    $_SESSION['account_error'] = 'Username cannot be empty.';
    header('Location: admin/account');
    exit();
}

// Determine target email for OTP
$target_email = !empty($new_email) ? $new_email : $admin['email'];

if (empty($target_email)) {
    $_SESSION['account_error'] = 'Please enter an email address to enable verification.';
    header('Location: admin/account');
    exit();
}

// Generate 6-digit OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

// Store pending changes in session
$_SESSION['pending_account'] = [
    'username'      => $new_username,
    'email'         => !empty($new_email) ? $new_email : $admin['email'],
    'password_hash' => !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : null,
    'otp'           => $otp,
    'otp_expiry'    => time() + 600,
];

// Send OTP via Resend
$sent = sendOtpEmail($target_email, $otp, $new_username);

if ($sent) {
    // Mask email for display: abc***@domain.com
    $parts = explode('@', $target_email);
    $masked = substr($parts[0], 0, 3) . '***@' . $parts[1];
    $_SESSION['show_otp_modal'] = true;
    $_SESSION['otp_email']      = $masked;
} else {
    unset($_SESSION['pending_account']);
    $_SESSION['account_error'] = 'Failed to send verification email. Please try again.';
}

header('Location: admin/account');
exit();

function sendOtpEmail(string $to, string $otp, string $username): bool {
    $api_key = 're_VpypC3CJ_2XMcgzShH5FVTFc7pxD9r1te';

    $html = "
    <div style='font-family: Arial, sans-serif; max-width: 520px; margin: 0 auto; padding: 24px; border-radius: 8px; border: 1px solid #e5e7eb;'>
        <div style='text-align:center; margin-bottom: 24px;'>
            <h2 style='color: #4c51bf; margin: 0;'>ESSU CCS Admin</h2>
            <p style='color: #6b7280; margin: 4px 0 0;'>Account Settings Verification</p>
        </div>
        <p style='color: #374151;'>Hello <strong>" . htmlspecialchars($username) . "</strong>,</p>
        <p style='color: #374151;'>Use the code below to verify your account changes. It expires in <strong>10 minutes</strong>.</p>
        <div style='background: #eef2ff; border-radius: 8px; padding: 24px; text-align: center; margin: 24px 0;'>
            <span style='font-size: 40px; font-weight: bold; letter-spacing: 10px; color: #4c51bf;'>{$otp}</span>
        </div>
        <p style='color: #6b7280; font-size: 0.875rem;'>If you did not request this change, please ignore this email.</p>
    </div>";

    $payload = json_encode([
        'from'    => 'ESSU CCS Admin <onboarding@resend.dev>',
        'to'      => [$to],
        'subject' => 'Your OTP Code – Account Settings',
        'html'    => $html,
    ]);

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
        ],
    ]);
    curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    return $http_code === 200;
}
