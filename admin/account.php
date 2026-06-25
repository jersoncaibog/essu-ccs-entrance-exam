<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../index.php');
    exit();
}

require_once __DIR__ . '/../classes/connection.php';

// Add email column if not yet present (compatible with MySQL 5.x)
$colCheck = $conn->query("SHOW COLUMNS FROM admin LIKE 'email'");
if ($colCheck->num_rows === 0) {
    $conn->query("ALTER TABLE admin ADD COLUMN email VARCHAR(255) DEFAULT NULL");
}

$adminId = $_SESSION['adminId'] ?? 1;
$stmt    = $conn->prepare("SELECT username, email FROM admin WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$adminData = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

$accountError   = $_SESSION['account_error']   ?? null;
$accountSuccess = $_SESSION['account_success'] ?? null;
$showOtpModal   = $_SESSION['show_otp_modal']  ?? false;
$otpEmail       = $_SESSION['otp_email']       ?? '';
unset($_SESSION['account_error'], $_SESSION['account_success'], $_SESSION['show_otp_modal'], $_SESSION['otp_email']);

$pageTitle  = 'Account Settings';
$activePage = 'account';
include __DIR__ . '/_layout.php';
?>

    <div class="page-header">
        <h1><i class="fas fa-user-cog"></i> Account Settings</h1>
    </div>

    <div class="card account-card">
        <?php if ($accountError): ?>
            <div class="account-alert account-alert-error"><?= htmlspecialchars($accountError) ?></div>
        <?php endif; ?>
        <?php if ($accountSuccess): ?>
            <div class="account-alert account-alert-success"><?= htmlspecialchars($accountSuccess) ?></div>
        <?php endif; ?>

        <form action="<?= $base ?>/update-admin-account.php" method="POST" class="account-form">
            <div class="account-field">
                <label for="new_username">Username</label>
                <input type="text" id="new_username" name="new_username"
                       value="<?= htmlspecialchars($adminData['username'] ?? '') ?>" required>
            </div>
            <div class="account-field">
                <label for="new_email">
                    Email <span class="field-note">(used for OTP verification)</span>
                </label>
                <input type="email" id="new_email" name="new_email"
                       value="<?= htmlspecialchars($adminData['email'] ?? '') ?>"
                       placeholder="your@email.com">
            </div>
            <hr class="account-divider">
            <p class="section-label">
                Change Password <span class="field-note">(leave blank to keep current)</span>
            </p>
            <div class="account-field">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="••••••••">
            </div>
            <div class="account-field">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••">
            </div>
            <hr class="account-divider">
            <div class="account-field">
                <label for="current_password">
                    Current Password <span class="field-note">(required to save changes)</span>
                </label>
                <input type="password" id="current_password" name="current_password" required placeholder="••••••••">
            </div>
            <button type="submit" class="account-save-btn">
                <i class="fas fa-paper-plane"></i> Send Verification &amp; Save
            </button>
        </form>
    </div>

</div><!-- /.main-content -->

<!-- OTP Verification Modal -->
<div id="otp-overlay" class="otp-overlay" style="display:none;"></div>
<div id="otp-modal" class="otp-modal" style="display:none;">
    <h2><i class="fas fa-envelope-open-text"></i> Email Verification</h2>
    <p>A 6-digit code was sent to <strong id="otp-email-display"></strong>. Enter it below to apply your changes.</p>
    <form action="<?= $base ?>/verify-admin-otp.php" method="POST" class="otp-form">
        <input type="text" name="otp" id="otp-input" maxlength="6" placeholder="000000"
               pattern="\d{6}" inputmode="numeric" autocomplete="one-time-code" required>
        <div class="otp-buttons">
            <button type="submit" class="otp-submit-btn">Verify &amp; Apply</button>
            <a href="<?= $base ?>/admin/account" class="otp-cancel-link">Cancel</a>
        </div>
    </form>
</div>

<?php if ($showOtpModal): ?>
<script>
(function () {
    document.getElementById("otp-email-display").textContent = <?= json_encode($otpEmail) ?>;
    document.getElementById("otp-overlay").style.display = "block";
    document.getElementById("otp-modal").style.display   = "block";
    document.getElementById("otp-input").focus();
})();
</script>
<?php endif; ?>

</body>
</html>
