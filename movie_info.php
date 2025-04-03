<?php
session_start();
require_once 'sql/queries.php';

// ✅ Helper to resolve poster path
function resolvePosterPath($path) {
    if (!$path) return 'images/image_not_found.jpg';

    // API-style path (e.g., "/abc.jpg")
    if (str_starts_with($path, '/')) return 'https://image.tmdb.org/t/p/w500' . $path;

    // Absolute URL (already hosted)
    if (str_starts_with($path, 'http')) return $path;

    // Local file (uploaded)
    return file_exists(__DIR__ . '/' . $path) ? $path : 'images/image_not_found.jpg';
}

$movieId = $_GET['id'] ?? null;
if (!$movieId) die("Movie ID not provided.");

$isLocalMovie = $movieId >= 10000000;
$reviews = getReviewsByMovieId($movieId);

// Fetch movie data
if ($isLocalMovie) {
    $movieData = getAllMoviesByMovieId($movieId);
    if (!$movieData) die("Local movie not found.");

    $title = $movieData['movie_title'];
    $poster = resolvePosterPath($movieData['img_link'] ?? null);
    $overview = $movieData['movie_description'];
    $rating = $movieData['ratings'] ?? 'N/A';
    $release = 'N/A';
    $genres = $movieData['genre'];
    $videoKey = null;
    $cast = [];
    $recommendations = [];
} else {
    $response = fetchMovieDetails($movieId);
    if (!$response || !isset($response['movieData'])) {
        die("API Movie not found.");
    }

    $movieData = $response['movieData'];
    $title = $movieData['title'];
    $poster = $movieData['poster'];
    $overview = $movieData['overview'];
    $rating = $movieData['rating'];
    $release = $movieData['release'];
    $genres = $movieData['genres'];
    $videoKey = $movieData['videoKey'];
    $cast = $movieData['cast'];
    $recommendations = $movieData['recommendations'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($title) ?> - MovieVerse</title>
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
<?php include 'inc/nav.inc.php'; ?>

<div class="movie-detail-container">
  <img src="<?= htmlspecialchars($poster) ?>" class="movie-detail-poster" alt="<?= htmlspecialchars($title) ?>">

  <div class="movie-info-cast-wrap">
    <div class="movie-detail-info">
      <h1><?= htmlspecialchars($title) ?></h1>
      <p><strong>Release:</strong> <?= htmlspecialchars($release) ?></p>
      <p><strong>Rating:</strong> ⭐ <?= htmlspecialchars($rating) ?></p>
      <p><strong>Genres:</strong> <?= htmlspecialchars($genres) ?></p>
      <p><strong>Overview:</strong><br><?= nl2br(htmlspecialchars($overview)) ?></p>

      <!-- AJAX-based watchlist button -->
      <button class="btn" style="margin-top: 1rem;" onclick="addToWatchlist(<?= $movieId ?>)">
        + Add to Watchlist
      </button>

      <div style="margin-top: 1rem;">
        <a href="submit_review.php?id=<?= $movieId ?>">
          <button class="btn-review">✍️ Write a Review</button>
        </a>
      </div>
    </div>

    <?php if (!$isLocalMovie && !empty($cast)): ?>
    <div class="movie-cast">
      <h2>👥 Cast</h2>
      <ul>
        <?php foreach ($cast as $actor): ?>
          <li><?= htmlspecialchars($actor['name']) ?><br><small>as <?= htmlspecialchars($actor['character']) ?></small></li>
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
    <div class="reviews-scroll">
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
    </div>
  <?php endif; ?>
</div>

<?php if (!$isLocalMovie): ?>
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
<?php endif; ?>

<?php include 'inc/footer.inc.php'; ?>
</body>
</html>
