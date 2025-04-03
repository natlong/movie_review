<?php
session_start();

// ✅ Admin Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Include common header and navigation
include 'inc/head.inc.php';
include 'inc/nav.inc.php';

// Connect to DB and fetch movie requests from the "requests" table
require_once 'sql/queries.php';
$conn = connectToDB();



// Fetch all requests
$sql = "SELECT * FROM requests";
$result = $conn->query($sql);

// Handle review form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['movie_id'], $_POST['review'], $_POST['rating'])) {
    $userId = $_SESSION['user_id'] ?? null;
    $movieId = intval($_POST['movie_id']);
    $review = trim($_POST['review']);
    $rating = floatval($_POST['rating']);

    if ($userId && $movieId && $review !== '' && $rating >= 0 && $rating <= 10) {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, movie_id, review, rating, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iisd", $userId, $movieId, $review, $rating);
        if ($stmt->execute()) {
            $reviewMessage = "✅ Review submitted successfully.";
        } else {
            $reviewError = "❌ Failed to submit review.";
        }
        $stmt->close();
    } else {
        $reviewError = "❌ Invalid review submission.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert New Movie</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
      .table-container {
          max-height: 400px;
          overflow-y: auto;
      }
    </style>
</head>
<body>
<main class="container mt-5">
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success" role="alert" aria-live="polite">
            <?php echo htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert" aria-live="assertive">
            <?php echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Review success/error messages -->
    <?php if (!empty($reviewMessage)): ?>
        <div class="alert alert-success"><?= $reviewMessage ?></div>
    <?php elseif (!empty($reviewError)): ?>
        <div class="alert alert-danger"><?= $reviewError ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- LEFT COLUMN: Movie Requests Table -->
        <div class="col-md-6 mb-4">
            <h2>Movie Requests</h2>
            <div class="table-container" role="region" aria-label="List of movie requests">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Requested Movie</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php $counter = 1; ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($row['request_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <a href="delete.php?id=<?php echo urlencode($row['idrequests']); ?>" 
                                           class="btn btn-sm btn-danger" role="button"
                                           onclick="return confirm('Are you sure you want to delete this request?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT COLUMN: Insert New Movie Form -->
        <div class="col-md-6">
            <h2>Insert New Movie</h2>
            <form action="insert_process.php" method="post" enctype="multipart/form-data" aria-label="Insert new movie form">
                <div class="form-group">
                    <label for="movie_title">Movie Title</label>
                    <input type="text" id="movie_title" name="movie_title" class="form-control" required aria-required="true">
                </div>
                <div class="form-group">
                    <label for="movie_description">Description</label>
                    <textarea id="movie_description" name="movie_description" class="form-control" rows="4" required aria-required="true"></textarea>
                </div>
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" id="genre" name="genre" class="form-control" required aria-required="true">
                </div>
                <div class="form-group">
                    <label for="poster">Movie Poster</label>
                    <input type="file" id="poster" name="poster" class="form-control-file" accept="image/*" required aria-required="true">
                </div>
                <button type="submit" class="btn btn-success">Insert Movie</button>
            </form>

            <hr>

        </div>
    </div>
</main>
<?php 
$conn->close();
include 'inc/footer.inc.php'; 
?>
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>