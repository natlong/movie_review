<?php
session_start();

// Show errors for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Make sure this is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: insert.php?error=" . urlencode("Invalid request method."));
    exit();
}

// Include DB connection from your existing queries.php
require_once 'sql/queries.php';
$conn = connectToDB(); // get $conn from your config

$errorMsg = "";
$success = true;

// Sanitize function
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Validate and sanitize inputs
if (empty($_POST['movie_title'])) {
    $errorMsg .= "Movie title is required. ";
    $success = false;
} else {
    $movieTitle = sanitize_input($_POST['movie_title']);
}

if (empty($_POST['movie_description'])) {
    $errorMsg .= "Movie description is required. ";
    $success = false;
} else {
    $movieDescription = sanitize_input($_POST['movie_description']);
}

if (empty($_POST['genre'])) {
    $errorMsg .= "Genre is required. ";
    $success = false;
} else {
    $genre = sanitize_input($_POST['genre']);
}

// Validate file upload
if (!isset($_FILES['poster']) || $_FILES['poster']['error'] !== UPLOAD_ERR_OK) {
    $errorMsg .= "Poster image is required and must be uploaded correctly. ";
    $success = false;
} else {
    $posterFile = $_FILES['poster'];
}

// File handling
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

// Move uploaded file
if ($success) {
    $safeTitle = preg_replace("/[^A-Za-z0-9_\-]/", "_", $movieTitle);
    $newFileName = $safeTitle . '.' . $fileExt;
    $uploadDir = __DIR__ . "/imgs/";
    $destPath = $uploadDir . $newFileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        $errorMsg .= "Failed to move uploaded file. ";
        $success = false;
    }
}

// Final DB insert
if ($success && isset($conn)) {
    $imgLink = "imgs/" . $newFileName;

    $stmt = $conn->prepare("INSERT INTO movie (movie_title, movie_description, genre, img_link) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $errorMsg .= "Prepare failed: " . $conn->error;
        $success = false;
    } else {
        $stmt->bind_param("ssss", $movieTitle, $movieDescription, $genre, $imgLink);
        if (!$stmt->execute()) {
            $errorMsg .= "Insert failed: " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }

    $conn->close();
}

// Redirect with status
if ($success) {
    header("Location: insert.php?message=" . urlencode("✅ Movie inserted successfully."));
    exit();
} else {
    header("Location: insert.php?error=" . urlencode($errorMsg));
    exit();
}
?>
