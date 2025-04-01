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
 * Sanitize input
 */
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validate form input
if (empty($_POST['movie_name'])) {
    $errorMsg = "Movie name is required.";
    $success = false;
} else {
    $movieName = sanitize_input($_POST['movie_name']);
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

// ✅ Step 2: Connect to DB
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

// ✅ Step 3: Insert into `requests` table
if ($success) {
    // Optional: Check if already exists in the `movie` table
    $stmt = $conn->prepare("SELECT COUNT(*) FROM movie WHERE movie_title = ?");
    $stmt->bind_param("s", $movieName);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $conn->close();
        header("Location: request.php?error=" . urlencode("This movie already exists in our database."));
        exit();
    }

    // Insert into requests table
    $stmt2 = $conn->prepare("INSERT INTO requests (request_name) VALUES (?)");
    if (!$stmt2) {
        $errorMsg = "Insert preparation failed: " . $conn->error;
        $success = false;
    } else {
        $stmt2->bind_param("s", $movieName);
        if (!$stmt2->execute()) {
            $errorMsg = "Insert failed: " . $stmt2->error;
            $success = false;
        }
        $stmt2->close();
    }

    $conn->close();
}

// ✅ Step 4: Redirect back with result
if ($success) {
    header("Location: request.php?message=" . urlencode("✅ Movie request submitted successfully."));
    exit();
} else {
    header("Location: request.php?error=" . urlencode("❌ " . $errorMsg));
    exit();
}
?>

<?php
    $servername = "localhost";
    $username = "sqldev";
    $password = "sqldev@s1t";
    $dbname = "movie_review_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?> -->
