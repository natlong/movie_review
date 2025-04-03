<?php
session_start();
require_once 'sql/queries.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

$oldPassword = $_POST['old_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if ($newPassword !== $confirmPassword) {
    header("Location: profile.php?error=" . urlencode("Passwords do not match"));
    exit;
}

$conn = connectToDB();
$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($oldPassword, $user['password'])) {
    header("Location: profile.php?error=" . urlencode("Incorrect current password"));
    exit;
}

$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
$update->bind_param("si", $hashedNewPassword, $userId);
$update->execute();

header("Location: profile.php?message=" . urlencode("Password updated successfully"));
?>
