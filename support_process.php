<?php
session_start();

// Only allow POST requests.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: support.php?error=" . urlencode("Invalid request method."));
    exit();
}

$errorMsg = "";
$success = true;

/**
 * Sanitize input to mitigate XSS.
 */
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Ensure the user is logged in and that user_id, email, and username are available.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['username'])) {
    $errorMsg = "User must be logged in to submit a support ticket. Please login or email support@movieverse.com";
    $success = false;
}

// Validate form input.
if (empty($_POST['question'])) {
    $errorMsg = "Support message is required.";
    $success = false;
} else {
    $message = sanitize_input($_POST['question']);
}

// Step 1: Load the DB config file.
$config_path = "/var/www/private/db-config.ini";
if (!file_exists($config_path)) {
    $errorMsg = "Config file does not exist.";
    $success = false;
} elseif (!is_readable($config_path)) {
    $errorMsg = "Config file is not readable.";
    $success = false;
} else {
    $config = parse_ini_file($config_path);
    if (!$config || !isset($config['servername'], $config['username'], $config['password'], $config['dbname'])) {
        $errorMsg = "Invalid config format.";
        $success = false;
    }
}

// Step 2: Connect to the DB.
if ($success) {
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );
    if ($conn->connect_error) {
        $errorMsg = "DB connection failed: " . $conn->connect_error;
        $success = false;
    }
}

// Step 3: Insert into support_messages table.
if ($success) {
    $user_id    = $_SESSION['user_id'];
    $user_email = $_SESSION['email'];
    $username   = $_SESSION['username'];
    
    $stmt = $conn->prepare("INSERT INTO support_messages (user_id, user_email, username, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $errorMsg = "Insert preparation failed: " . $conn->error;
        $success = false;
    } else {
        // Bind parameters: i for user_id, and s for email, username, and message.
        $stmt->bind_param("isss", $user_id, $user_email, $username, $message);
        if (!$stmt->execute()) {
            $errorMsg = "Insert failed: " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();
}

// Step 4: Redirect back to support.php with result.
if ($success) {
    header("Location: support.php?message=" . urlencode("✅ Support ticket submitted successfully."));
    exit();
} else {
    header("Location: support.php?error=" . urlencode("❌ " . $errorMsg));
    exit();
}
?>
