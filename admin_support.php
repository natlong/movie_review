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
  <!-- Responsive meta tag -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Support Messages</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

  <!-- Optional: Additional overrides for dark mode -->
  <style>
    body {
      background-color: #000; /* Example dark background */
      color: #fff;            /* Ensure default text is white */
    }
    .table-responsive {
      margin-top: 20px;
    }
    /* If needed, override table border or link colors, etc. */
  </style>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <main class="container my-4" role="main">
    <h1 class="mb-4">Support Messages</h1>
    
    <?php
    // Load database configuration.
    $config_path = "/var/www/private/db-config.ini";
    if (!file_exists($config_path) || !is_readable($config_path)) {
        die("<div class='alert alert-danger' role='alert'>DB config file missing or not readable.</div>");
    }
    $config = parse_ini_file($config_path);
    if (!$config || !isset($config['servername'], $config['username'], $config['password'], $config['dbname'])) {
        die("<div class='alert alert-danger' role='alert'>Invalid DB config.</div>");
    }
    
    // Connect to the database.
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );
    if ($conn->connect_error) {
        die("<div class='alert alert-danger' role='alert'>Connection failed: " . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8') . "</div>");
    }
    
    // Query support messages
    $sql = "SELECT support_id, username, user_email, message, created_at 
            FROM support_messages 
            ORDER BY created_at DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0):
    ?>
    <div class="table-responsive">
      <!-- Use .table-dark to ensure white text on dark background -->
      <table class="table table-dark table-hover table-striped" aria-describedby="table-description">
        <caption id="table-description" class="sr-only">
          A table displaying all support messages submitted by users.
        </caption>
        <thead>
          <tr>
            <th scope="col">Support ID</th>
            <th scope="col">Username</th>
            <th scope="col">User Email</th>
            <th scope="col">Message</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['support_id'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['user_email'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
      <div class="alert alert-info" role="alert">No support messages found.</div>
    <?php 
      endif;
      $conn->close();
    ?>
  </main>
  
  <!-- Bootstrap JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
