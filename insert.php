<?php
session_start();
include 'inc/head.inc.php';
include 'inc/nav.inc.php';

// Connect to DB and fetch requests
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

// Fetch all movie requests
$sql = "SELECT * FROM requests";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Insert New Movie</title>
    <!-- Include any additional CSS or meta tags as needed -->
    <style>
      /* Optional: Limit table height and enable scrolling */
      .table-container {
          max-height: 400px;
          overflow-y: auto;
      }
    </style>
</head>
<body>
<main class="container mt-5">
    <div class="row">
        <!-- LEFT COLUMN: Dynamic table of movie requests -->
        <div class="col-md-6 mb-4">
            <h2>Movie Requests</h2>
            <div class="table-container">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Requested Movie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['idrequests'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['request_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">No requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- RIGHT COLUMN: Insert New Movie Form -->
        <div class="col-md-6">
            <h2>Insert New Movie</h2>
            <form action="insert_process.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="movie_title">Movie Title</label>
                    <input type="text" id="movie_title" name="movie_title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="movie_description">Description</label>
                    <textarea id="movie_description" name="movie_description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" id="genre" name="genre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="poster">Movie Poster</label>
                    <input type="file" id="poster" name="poster" class="form-control-file" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success">Insert Movie</button>
            </form>
        </div>
    </div>
</main>
<?php 
$conn->close();
include 'inc/footer.inc.php'; 
?>
</body>
</html>