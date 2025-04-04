<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: insert.php?error=" . urlencode("Invalid request method."));
    exit();
}

require_once 'sql/queries.php';
$conn = connectToDB();

$errorMsg = "";
$success = true;

// Sanitize helper
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Validate fields
$movieTitle = !empty($_POST['movie_title']) ? sanitize_input($_POST['movie_title']) : ($errorMsg .= "Movie title is required. ") && $success = false;
$movieDescription = !empty($_POST['movie_description']) ? sanitize_input($_POST['movie_description']) : ($errorMsg .= "Movie description is required. ") && $success = false;
$genre = !empty($_POST['genre']) ? sanitize_input($_POST['genre']) : ($errorMsg .= "Genre is required. ") && $success = false;

// Poster validation
if (!isset($_FILES['poster']) || $_FILES['poster']['error'] !== UPLOAD_ERR_OK) {
    $errorMsg .= "Poster image is required and must be uploaded correctly. ";
    $success = false;
} else {
    $posterFile = $_FILES['poster'];
}

// Handle file
if ($success) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileTmpPath = $posterFile['tmp_name'];
    $fileName = $posterFile['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedExtensions)) {
        $errorMsg .= "Invalid file type. Allowed types: jpg, jpeg, png, gif. ";
        $success = false;
    }
}

// Upload + move
if ($success) {
    $safeTitle = preg_replace("/[^A-Za-z0-9_\-]/", "_", $movieTitle);
    $newFileName = $safeTitle . '_' . time() . '.' . $fileExt;

    $uploadDir = __DIR__ . "/imgs/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $destPath = $uploadDir . $newFileName;
    $relativePath = "imgs/" . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        $errorMsg .= "Failed to move uploaded file. ";
        $success = false;
    }
}

// Insert into DB
if ($success && $conn) {
    $stmt = $conn->prepare("INSERT INTO movie (movie_title, movie_description, genre, img_link) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $errorMsg .= "DB prepare failed: " . $conn->error;
        $success = false;
    } else {
        $stmt->bind_param("ssss", $movieTitle, $movieDescription, $genre, $relativePath);
        if (!$stmt->execute()) {
            $errorMsg .= "Insert failed: " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();
}

// Redirect result
header("Location: insert.php?" . ($success ? "message=✅ Movie inserted successfully." : "error=" . urlencode($errorMsg)));
exit();
?>
