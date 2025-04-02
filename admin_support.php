<?php
session_start();

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - View Support Messages</title>
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
  <style>
    .container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }
    table, th, td {
      border: 1px solid #ccc;
    }
    th, td {
      padding: 12px;
      text-align: left;
    }
    th {
      background: #f4f4f4;
    }
  </style>
</head>
<body>
  <?php include 'inc/nav.inc.php'; // Optional: your site’s navbar ?>

  <div class="container">
    <h1>Support Messages</h1>

    <?php
    // Load DB config (same style as request_process.php)
    $config_path = "/var/www/private/db-config.ini";
    if (!file_exists($config_path) || !is_readable($config_path)) {
        die("DB config file missing or not readable.");
    }
    $config = parse_ini_file($config_path);
    if (!$config || !isset($config['servername'], $config['username'], $config['password'], $config['dbname'])) {
        die("Invalid DB config.");
    }

    // Connect to the DB
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the support_messages table
    $sql = "SELECT support_id, user_id, message, created_at
            FROM support_messages
            ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Support ID</th>
                <th>User ID</th>
                <th>Message</th>
                <th>Created At</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['support_id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No support messages found.</p>";
    }

    $conn->close();
    ?>
  </div>
</body>
</html>
