<?php
session_start();

// Check for admin access (adjust based on your session setup)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php?error=Unauthorized");
    exit();
}

// Validate that the request ID is provided via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: insert.php?error=Missing+request+ID");
    exit();
}

// Retrieve and validate the request ID
$requestId = intval($_GET['id']);  // ensures it's an integer

// Connect to DB using configuration from db-config.ini
$config = parse_ini_file(__DIR__ . '/private/db-config.ini');
if (!$config) {
    die("Error: Could not read DB configuration file.");
}

$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['dbname']
);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a DELETE statement to remove the request from the requests table
$stmt = $conn->prepare("DELETE FROM requests WHERE idrequests = ?");
if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $requestId);

if (!$stmt->execute()) {
    die("Deletion failed: (" . $stmt->errno . ") " . $stmt->error);
}

$stmt->close();
$conn->close();

// Redirect back to insert.php with a success message
header("Location: insert.php?message=Request+deleted+successfully");
exit();
?>
