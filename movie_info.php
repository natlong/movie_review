<?php
session_start();
require_once 'sql/queries.php';
$movieId = $_GET['id'] ?? null;
if (!$movieId) die("Movie ID not provided.");
$response = fetchMovieDetails($movieId);
$reviews = getReviewsByMovieId($movieId);


if (!$response) {
    die("Movie not found.");
}

$movieData = $response['movieData'];
$movieExists = true; // Assume the movie exists since it's fetched from API

// Assign variables
$title = $movieData['title'];
$poster = $movieData['poster'];
$overview = $movieData['overview'];
$rating = $movieData['rating'];
$release = $movieData['release'];
$genres = $movieData['genres'];
$videoKey = $movieData['videoKey'];
$cast = $movieData['cast'];
$recommendations = $movieData['recommendations'];

$reviews = getReviewsByMovieId($movieId);
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $title ?> - MovieVerse</title>
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
<?php include 'inc/nav.inc.php'; ?>

<div class="movie-detail-container">
  <img src="<?= $poster ?>" class="movie-detail-poster" alt="<?= $title ?>">

  <div class="movie-info-cast-wrap">
    <div class="movie-detail-info">
      <h1><?= $title ?></h1>
      <p><strong>Release:</strong> <?= $release ?></p>
      <p><strong>Rating:</strong> ⭐ <?= $rating ?></p>
      <p><strong>Genres:</strong> <?= $genres ?></p>
      <p><strong>Overview:</strong><br><?= $overview ?></p>

      <form action="add_to_watchlist.php" method="POST" style="margin-top: 1rem;">
        <input type="hidden" name="movie_id" value="<?= $movieId ?>">
        <button class="btn">+ Add to Watchlist</button>
      </form>

      <div style="margin-top: 1rem;">
        <a href="submit_review.php?id=<?= $movieId ?>">
          <button class="btn-review">✍️ Write a Review</button>
        </a>
      </div>
    </div>

    <?php if (!empty($cast)): ?>
    <div class="movie-cast">
      <h2>👥 Cast</h2>
      <ul>
        <?php foreach ($cast as $actor): ?>
          <li><?= $actor['name'] ?><br><small>as <?= $actor['character'] ?></small></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Review Section -->
<div class="reviews-container">
  <h2>User Reviews</h2>
  <?php if (empty($reviews)): ?>
    <p>No reviews yet. Be the first to write one!</p>
  <?php else: ?>
    <?php foreach ($reviews as $review): ?>
      <div class="review-box">
        <div class="review-header">
          <img src="<?= $review['profile_pic'] ?: 'images/default-profile.png' ?>" alt="Profile Pic" class="review-profile-pic">
          <strong><?= htmlspecialchars($review['username']) ?></strong>
          <span class="review-date"><?= date("Y-m-d H:i", strtotime($review['created_at'])) ?></span>
        </div>
        <div class="review-body">
          <p><?= nl2br(htmlspecialchars($review['review'])) ?></p>
          <p>⭐ <?= number_format($review['rating'], 1) ?>/5</p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<div class="movie-bottom-row">
  <?php if ($videoKey): ?>
  <div class="movie-trailer">
    <h2>🎬 Trailer</h2>
    <iframe width="100%" height="315"
      src="https://www.youtube.com/embed/<?= $videoKey ?>"
      frameborder="0" allowfullscreen></iframe>
  </div>
  <?php endif; ?>

  <?php if (!empty($recommendations)): ?>
  <div class="movie-recommendations">
    <h2>🎞️ Recommended</h2>
    <div class="scroll-container">
      <?php foreach ($recommendations as $rec): ?>
        <a href="movie_info.php?id=<?= $rec['id'] ?>" class="movie-link" style="text-decoration: none;">
          <div class="movie-card">
            <img src="https://image.tmdb.org/t/p/w500<?= $rec['poster_path'] ?>" alt="<?= $rec['title'] ?>" class="movie-img">
            <h3><?= htmlspecialchars($rec['title']) ?></h3>
            <p><?= $rec['release_date'] ?? 'TBA' ?> • ⭐ <?= number_format($rec['vote_average'], 1) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php include 'inc/footer.inc.php'; ?>
</body>
</html>
