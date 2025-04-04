<?php
session_start();

// Ensure POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>
            alert('❌ Invalid request method.');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

// Sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validate form input
if (empty($_POST['movie_name'])) {
    echo "<script>
            alert('❌ Movie name is required.');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

$movieName = sanitize_input($_POST['movie_name']);

// Load DB config
$config_path = "/var/www/private/db-config.ini";
if (!file_exists($config_path) || !is_readable($config_path)) {
    echo "<script>
            alert('❌ Config file missing or unreadable.');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

$config = parse_ini_file($config_path);
if (!$config || !isset($config['servername'], $config['username'], $config['password'], $config['dbname'])) {
    echo "<script>
            alert('❌ Invalid config format.');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

// Connect to DB
$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['dbname']
);

if ($conn->connect_error) {
    echo "<script>
            alert('❌ DB connection failed: " . addslashes($conn->connect_error) . "');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

// ✅ Check if movie already exists in movie table
$stmt = $conn->prepare("SELECT COUNT(*) FROM movie WHERE movie_title = ?");
$stmt->bind_param("s", $movieName);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    $conn->close();
    echo "<script>
            alert('❌ This movie already exists in our collection.');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

// ✅ Check if movie is already requested
$stmt = $conn->prepare("SELECT COUNT(*) FROM requests WHERE request_name = ?");
$stmt->bind_param("s", $movieName);
$stmt->execute();
$stmt->bind_result($reqCount);
$stmt->fetch();
$stmt->close();

if ($reqCount > 0) {
    $conn->close();
    echo "<script>
            alert('❌ This movie has already been requested.');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

// ✅ Insert new request
$stmt = $conn->prepare("INSERT INTO requests (request_name) VALUES (?)");
if (!$stmt) {
    $conn->close();
    echo "<script>
            alert('❌ Insert preparation failed: " . addslashes($conn->error) . "');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

$stmt->bind_param("s", $movieName);
if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    echo "<script>
            alert('❌ Insert failed: " . addslashes($stmt->error) . "');
            setTimeout(function() {
                window.location.href='request.php';
            }, 100);
          </script>";
    exit();
}

$stmt->close();
$conn->close();

// ✅ Success message
echo "<script>
        alert('✅ Movie request submitted successfully.');
        setTimeout(function() {
            window.location.href='request.php';
        }, 100);
      </script>";
exit();
?>
