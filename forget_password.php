<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'sql/queries.php'; // your DB connection
    $email = $_POST['email'];

    // Generate a secure token
    $token = bin2hex(random_bytes(32));

    // Save it in DB
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expiry, $email);

    $stmt->execute();

    // Send reset link (local version - show link directly)
    $resetLink = "http://35.212.255.156/reset_password.php?token=$token";
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Click the link to reset your password: $resetLink";
    $headers = "From: no-reply@MovieVerse.com";
    if (mail($to, $subject, $message, $headers)) {
        echo "Password reset link has been sent to your email.";
    } else {
        echo "Failed to send email.";
    }
}
?>

<form method="POST">
    <input type="email" name="email" required placeholder="Enter your email" />
    <button type="submit">Send Reset Link</button>
</form>
