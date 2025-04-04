<?php
// reset_password.php

require_once 'sql/queries.php'; // your DB connection
if (!isset($_GET['token'])) {
    die('Invalid request. No token provided.');
    exit;
}
$token = $_GET['token'] ?? '';

// Validate the token
if (!$token) {
    die('Invalid or missing token.');
}

$stmt = $conn->prepare("SELECT id, reset_token_expiry FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die('Invalid token.');
}

// Optional: Check if token expired
if (!empty($user['reset_token_expiry']) && strtotime($user['reset_token_expiry']) < time()) {
    die('Reset link expired.');
}

// ✅ If passed, show the form to reset the password
?>
<form method="POST" action="reset_password_process.php">
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
  <input type="password" name="new_password" placeholder="New Password" required>
  <button type="submit">Reset Password</button>
</form>
