<?php
session_start();

// Ensure the form is submitted via POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: request.php?error=" . urlencode("Invalid request method."));
    exit();
}

$errorMsg = "";
$success = true;

/**
 * Helper function to sanitize input data.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Validate movie name input
if (empty($_POST['movie_name'])) {
    $errorMsg .= "Movie name is required.";
    $success = false;
} else {
    $movieName = sanitize_input($_POST['movie_name']);
}

// If validation fails, redirect back with error message.
if (!$success) {
    header("Location: request.php?error=" . urlencode($errorMsg));
    exit();
}

// Connect to the database.
$config = parse_ini_file(__DIR__ . '/private/db-config.ini');
if (!$config) {
    $errorMsg .= "Failed to read DB configuration file.";
    $success = false;
}

$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['dbname']
);
if ($conn->connect_error) {
    $errorMsg .= "Connection failed: " . $conn->connect_error;
    $success = false;
}

if ($success) {
    // Prepare the INSERT statement for the "requests" table.
    $stmt = $conn->prepare("INSERT INTO requests (request_name) VALUES (?)");
    if (!$stmt) {
        $errorMsg .= "Statement preparation failed: " . $conn->error;
        $success = false;
    } else {
        $stmt->bind_param("s", $movieName);
        if (!$stmt->execute()) {
            $errorMsg .= "Insert failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }
}
$conn->close();

// Redirect back to request.php with a message.
if ($success) {
    header("Location: request.php?message=" . urlencode("Movie request inserted successfully."));
} else {
    header("Location: request.php?error=" . urlencode($errorMsg));
}
exit();
?>
