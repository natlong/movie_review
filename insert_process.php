<?php
session_start();

// Ensure this script is only called via POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: insert.php?error=" . urlencode("Invalid request method."));
    exit();
}

$errorMsg = "";
$success = true;

// Validate required fields
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

// Process the poster file upload
if (!isset($_FILES['poster']) || $_FILES['poster']['error'] != UPLOAD_ERR_OK) {
    $errorMsg .= "Poster image is required and must be uploaded correctly. ";
    $success = false;
} else {
    $posterFile = $_FILES['poster'];
}

if ($success) {
    // Allowed image file extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileTmpPath = $posterFile['tmp_name'];
    $fileName = $posterFile['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedExtensions)) {
        $errorMsg .= "Invalid file type. Allowed types: " . implode(", ", $allowedExtensions) . ". ";
        $success = false;
    }
}

if ($success) {
    // Create a safe file name from the movie title by replacing non-alphanumeric characters with underscores
    $safeTitle = preg_replace("/[^A-Za-z0-9_\-]/", "_", $movieTitle);
    $newFileName = $safeTitle . '.' . $fileExt;

    // Destination folder for uploaded images (ensure this folder exists and is writable)
    $uploadDir = "/var/www/html/imgs/";
    $destPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        $errorMsg .= "Error moving the uploaded file. ";
        $success = false;
    }
}

if ($success) {
    // Build the relative image path to be stored in the database
    $imgLink = "/imgs/" . $newFileName;

    // Read database configuration from the INI file.
    // Adjust the path as needed; here we assume it's in a "private" folder relative to the project root.
    $config = parse_ini_file(__DIR__ . '/private/db-config.ini');
    if (!$config) {
        $errorMsg .= "Failed to read database config file. ";
        $success = false;
    } else {
        // Establish a connection using mysqli
        $conn = new mysqli(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['dbname']
        );
        if ($conn->connect_error) {
            $errorMsg .= "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            // Prepare a parameterized INSERT statement
            $stmt = $conn->prepare("INSERT INTO movie (movie_title, movie_description, genre, img_link) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                $errorMsg .= "Statement preparation failed: " . $conn->error;
                $success = false;
            } else {
                $stmt->bind_param("ssss", $movieTitle, $movieDescription, $genre, $imgLink);
                if (!$stmt->execute()) {
                    $errorMsg .= "Insert failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                }
                $stmt->close();
            }
            $conn->close();
        }
    }
}

/**
 * Helper function to sanitize input data.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Redirect back to insert.php with a message
if ($success) {
    header("Location: insert.php?message=" . urlencode("Movie inserted successfully."));
    exit();
} else {
    header("Location: insert.php?error=" . urlencode($errorMsg));
    exit();
}
?>
