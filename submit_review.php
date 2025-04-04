<?php
session_start();
require_once 'sql/queries.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please log in first."));
    exit();
}

$movieId = $_GET['id'] ?? null;
if (!$movieId) {
    die("Movie ID is required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $reviewText = trim($_POST['review']);
    $rating = intval($_POST['rating']);

    if ($reviewText && $rating >= 1 && $rating <= 5) {
        $conn = connectToDB();

        // If movie is not in the database, fetch from API and insert
        $checkMovie = $conn->prepare("SELECT movie_id FROM movie WHERE movie_id = ?");
        $checkMovie->bind_param("i", $movieId);
        $checkMovie->execute();
        $result = $checkMovie->get_result();

        if ($result->num_rows === 0) {
            // Movie not in DB, fetch from API
            $response = fetchMovieDetails($movieId);
            if ($response && isset($response['movieData'])) {
                $movie = $response['movieData'];
                $title = $movie['title'] ?? 'Untitled';
                $desc = $movie['overview'] ?? 'No description.';
                $genre = $movie['genres'] ?? 'Unknown';
                $poster = $movie['poster'] ?? '';

                $insertMovie = $conn->prepare("INSERT INTO movie (movie_id, movie_title, movie_description, genre, img_link) VALUES (?, ?, ?, ?, ?)");
                $insertMovie->bind_param("issss", $movieId, $title, $desc, $genre, $poster);
                $insertMovie->execute();
                $insertMovie->close();
            }
        }
        $checkMovie->close();

        // Insert review
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, movie_id, review, rating, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iisi", $userId, $movieId, $reviewText, $rating);
        if ($stmt->execute()) {
            header("Location: movie_info.php?id=" . urlencode($movieId) . "&message=Review submitted successfully.");
            exit();
        } else {
            $error = "Failed to submit review.";
        }
        $stmt->close();
        $conn->close();
    } else {
        $error = "Please enter a valid review and rating.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Write a Review</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <?php include 'inc/head.inc.php'; ?>
</head>
<body>
<?php include 'inc/nav.inc.php'; ?>

<main class="auth-form-container">
    <h1>Write a Review</h1>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label for="review">Your Review</label>
            <textarea name="review" id="review" rows="5" required></textarea>
        </div>

        <div class="input-group">
            <label for="rating">Rating (1-5)</label>
            <select name="rating" id="rating" required>
                <option value="">Select rating</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                <?php endfor; ?>
            </select>
        </div>

        <button type="submit" class="submit-btn">Submit Review</button>
    </form>
</main>

<?php include 'inc/footer.inc.php'; ?>
</body>
</html>
