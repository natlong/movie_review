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
 * Sanitize input
 */
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Ensure the user is logged in.
if (!isset($_SESSION['user_id'])) {
    $errorMsg = "User must be logged in to submit a support ticket.";
    $success = false;
}

// Validate form input.
if (empty($_POST['question'])) {
    $errorMsg = "Support message is required.";
    $success = false;
} else {
    $message = sanitize_input($_POST['question']);
}

// ✅ Step 1: Load config file
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

// ✅ Step 2: Connect to DB if everything is okay
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

// ✅ Step 3: Insert into `support_messages` table
if ($success) {
    $stmt = $conn->prepare("INSERT INTO support_messages (user_id, message) VALUES (?, ?)");
    if (!$stmt) {
        $errorMsg = "Insert preparation failed: " . $conn->error;
        $success = false;
    } else {
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param("is", $user_id, $message);
        if (!$stmt->execute()) {
            $errorMsg = "Insert failed: " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();
}

// ✅ Step 4: Redirect back to support.php with result.
if ($success) {
    header("Location: support.php?message=" . urlencode("✅ Support ticket submitted successfully."));
    exit();
} else {
    header("Location: support.php?error=" . urlencode("❌ " . $errorMsg));
    exit();
}
?>

