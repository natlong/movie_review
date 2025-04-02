<?php
session_start();
// Restrict access to admin users only.
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Support Messages</title>
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
  <!-- Inline CSS for table styling -->
  <style>
      .container {
          max-width: 1200px;
          margin: 20px auto;
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
          padding: 10px;
          text-align: left;
      }
      th {
          background-color: #f4f4f4;
      }
  </style>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <div class="container">
    <h1>Support Messages</h1>

    <?php
    // Load DB config.
    $config_path = "/var/www/private/db-config.ini";
    if (!file_exists($config_path) || !is_readable($config_path)) {
        die("DB config file missing or not readable.");
    }
    $config = parse_ini_file($config_path);
    if (!$config || !isset($config['servername'], $config['username'], $config['password'], $config['dbname'])) {
        die("Invalid DB config.");
    }
    
    // Connect to the database.
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Query support messages. We retrieve support_id, username, user_email, message, and created_at.
    $sql = "SELECT support_id, username, user_email, message, created_at 
            FROM support_messages 
            ORDER BY created_at DESC";
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Support ID</th>
                <th>Username</th>
                <th>User Email</th>
                <th>Message</th>
                <th>Created At</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['support_id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['user_email'], ENT_QUOTES, 'UTF-8') . "</td>";
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
