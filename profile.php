<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'sql/queries.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please log in first."));
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

$watchlist = getMovieFromWatchListByUserId($userId);
$hasWatchlist = is_array($watchlist) && count($watchlist) > 0;

$reviews = getReviewsByUserId($userId);
$hasReviews = is_array($reviews) && count($reviews) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Page</title>
  <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <div class="profile-container">
    <!-- Profile Info -->
    <div class="profile-details">
      <div class="profile-image-container">
        <img src="images/default-profile.png" alt="Profile Picture" class="profile-image">
      </div>
      <div class="profile-info">
        <h2><?= htmlspecialchars($username) ?></h2>
        <p>Email: <?= htmlspecialchars($email) ?></p>
        <a href="change_password.php" class="btn">Change Password</a>
      </div>
    </div>

    <!-- Watchlist Section -->
    <div class="watchlist-container">
      <h3>Your Watchlist</h3>

      <?php if (!$hasWatchlist): ?>
        <p>No watchlist yet.</p>
      <?php else: ?>
        <div class="scroll-container" id="movies-scroll">
          <?php foreach ($watchlist as $item): ?>
            <?php
              $movieId = $item['movie_id'];
              if (!$movieId) continue;

              $movieDetails = fetchMovieDetails($movieId);
              if (!$movieDetails || !isset($movieDetails['movieData'])) continue;

              $data = $movieDetails['movieData'];
            ?>
            <a href="movie_info.php?id=<?= $movieId ?>" class="movie-link">
              <div class="movie-card">
                <img src="<?= htmlspecialchars($data['poster']) ?>" alt="<?= htmlspecialchars($data['title']) ?>" class="movie-img">
                <h3><?= htmlspecialchars($data['title']) ?></h3>
                <p class="movie-info"><?= htmlspecialchars($data['release']) ?> • ⭐ <?= htmlspecialchars($data['rating']) ?></p>
                <button class="btn">+ Watchlist</button>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
<!-- Reviews Section -->
<div class="reviews-container">
  <h3>Your Movie Reviews</h3>

  <?php if (!$hasReviews): ?>
    <p>No reviews yet. Go write one!</p>
  <?php else: ?>
    <div class="user-reviews-list">
      <?php foreach ($reviews as $review): ?>
        <?php
          $movieDetails = fetchMovieDetails($review['movie_id']);
          $movieData = $movieDetails['movieData'] ?? null;
          $movieTitle = $movieData['title'] ?? 'Unknown Movie';
          $movieRating = isset($review['rating']) ? number_format($review['rating'], 1) : '0.0';
          $reviewText = nl2br(htmlspecialchars($review['review'] ?? ''));
          $reviewDate = isset($review['created_at']) ? date('F j, Y', strtotime($review['created_at'])) : 'Unknown';
        ?>
        <div class="review-box">
          <div class="review-header">
            <h4><?= htmlspecialchars($movieTitle) ?></h4>
            <span class="review-rating">⭐ <?= $movieRating ?></span>
          </div>
          <p class="review-text"><?= $reviewText ?></p>
          <div class="review-footer">
            <small>Posted on <?= $reviewDate ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>


  <?php include 'inc/footer.inc.php'; ?>
</body>
</html>
