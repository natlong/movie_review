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

// Validate movie name input.
if (empty($_POST['movie_name'])) {
    $errorMsg .= "Movie name is required.";
    $success = false;
} else {
    $movieName = sanitize_input($_POST['movie_name']);
}

if (!$success) {
    header("Location: request.php?error=" . urlencode($errorMsg));
    exit();
}

// Read database configuration.
$config = parse_ini_file(__DIR__ . '/private/db-config.ini');
if (!$config) {
    $errorMsg .= "Failed to read database config file.";
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
    // Check if the movie already exists in the movie table.
    $stmt = $conn->prepare("SELECT COUNT(*) FROM movie WHERE movie_title = ?");
    if (!$stmt) {
        $errorMsg .= "Statement preparation failed: " . $conn->error;
        $success = false;
    } else {
        $stmt->bind_param("s", $movieName);
        if (!$stmt->execute()) {
            $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            if ($count > 0) {
                // Movie already exists—redirect with error message.
                $conn->close();
                header("Location: request.php?error=" . urlencode("Movie already exists in the database."));
                exit();
            }
        }
    }
    
    // If movie doesn't exist, insert the new request into the requests table.
    $stmt2 = $conn->prepare("INSERT INTO requests (request_name) VALUES (?)");
    if (!$stmt2) {
        $errorMsg .= "Statement preparation failed: " . $conn->error;
        $success = false;
    } else {
        $stmt2->bind_param("s", $movieName);
        if (!$stmt2->execute()) {
            $errorMsg .= "Insert failed: (" . $stmt2->errno . ") " . $stmt2->error;
            $success = false;
        }
        $stmt2->close();
    }
    $conn->close();
}

if ($success) {
    header("Location: request.php?message=" . urlencode("Movie request inserted successfully."));
    exit();
} else {
    header("Location: request.php?error=" . urlencode($errorMsg));
    exit();
}
?>
