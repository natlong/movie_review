<?php
session_start();

// Include necessary header and navigation elements
include 'inc/head.inc.php';
include 'inc/nav.inc.php';

// Connect to the database using the configuration file.
require_once 'sql/queries.php';
$conn = connectToDB();

// Fetch only the top 5 most recent requests
$sql = "SELECT * FROM requests ORDER BY idrequests DESC LIMIT 5";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request - MovieVerse</title>
  <!-- If possible, include head.inc.php only once to avoid redundancy -->
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>

<main class="container mt-5">
  <header class="page-header mb-4 text-center">
    <h2>Request a Movie</h2>
    <p>Don't see the movie you're looking for? Request it here, and we’ll add it to our collection!</p>
  </header>

  <section class="request-form-container mb-4">
    <form id="request-form" action="request_process.php" method="post" aria-label="Movie Request Form">
      <div class="form-group">
        <label for="request-input" class="form-label">Movie Name</label>
        <input type="text" id="request-input" name="movie_name" class="form-control" placeholder="Enter movie name" required>
      </div>
      <div style="display: flex; justify-content: center; margin-top: 15px;">
        <button type="submit" class="btn btn-primary">Send Request</button>
      </div>
    </form>
  </section>

  <section class="recent-requests">
    <h3>Recently Added Movie Requests</h3>
    <ul class="list-group">
      <?php
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              // Escaping the output to prevent XSS
              echo '<li class="list-group-item">' . htmlspecialchars($row['request_name'], ENT_QUOTES, 'UTF-8') . '</li>';
          }
      } else {
          echo '<li class="list-group-item">No requests found.</li>';
      }
      ?>
    </ul>
  </section>
</main>

<?php 
$conn->close();
include 'inc/footer.inc.php'; 
?>

<!-- Link your request.js properly and ensure defer or DOMContentLoaded -->
<script src="/js/request.js?v=<?php echo time(); ?>" defer></script>

</body>
</html>
