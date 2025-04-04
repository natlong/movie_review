<?php
require_once 'sql/queries.php';
$token = $_POST['token'] ?? '';
$newPassword = $_POST['new_password'] ?? '';

if (!$token || !$newPassword) {
    die("Invalid request.");
}

// Re-validate token
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Invalid or expired token.");
}

// Hash and update password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
$stmt->bind_param("si", $hashedPassword, $user['id']);
$stmt->execute();

echo "Password successfully reset!";
?>
