<<<<<<< Updated upstream
<?php
session_start();
require_once 'sql/queries.php';

$movieId = $_GET['id'] ?? null;
if (!$movieId) die("Movie ID not provided.");

$isLocalMovie = $movieId >= 10000000;
$reviews = getReviewsByMovieId($movieId);

// Fetch movie data
if ($isLocalMovie) {
    $movieData = getAllMoviesByMovieId($movieId);
    if (!$movieData) die("Local movie not found.");

    $title = $movieData['movie_title'];
    $posterPath = $movieData['img_link'] ?? 'images/image_not_found.jpg';
    $poster = file_exists($posterPath) ? $posterPath : 'images/image_not_found.jpg';
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
=======
<?php
session_start();
require_once 'sql/queries.php';

$movieId = $_GET['id'] ?? null;
if (!$movieId) die("Movie ID not provided.");

$isLocalMovie = $movieId >= 10000000;
$reviews = getReviewsByMovieId($movieId);

function getPosterURL($posterPath) {
  if (!$posterPath) return '/images/image_not_found.jpg';
  if (str_starts_with($posterPath, '/')) {
      return 'https://image.tmdb.org/t/p/w200' . $posterPath;
  } elseif (str_starts_with($posterPath, 'imgs/') || str_starts_with($posterPath, 'uploads/')) {
      return '/' . $posterPath;
  }
  return '/images/image_not_found.jpg';
}

if ($isLocalMovie) {
  $movieData = getAllMoviesByMovieId($movieId);
  if (!$movieData) die("Local movie not found.");

  $movieData['poster']   = $movieData['img_link'];
  $movieData['title']    = $movieData['movie_title'];
  $movieData['overview'] = $movieData['movie_description'];
  $movieData['rating']   = $movieData['ratings'] ?? 'N/A';
  $movieData['release']  = 'N/A';
  $movieData['genres']   = $movieData['genre'];

  $title        = $movieData['title'];
  $poster       = getPosterURL($movieData['poster']);
  $overview     = $movieData['overview'];
  $rating       = $movieData['rating'];
  $release      = $movieData['release'];
  $genres       = $movieData['genres'];
  $videoKey     = null;
  $cast         = [];
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

      <button class="btn" style="margin-top: 1rem;" onclick="addToWatchlist(<?= $movieId ?>)">+ Add to Watchlist</button>

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
    <?php foreach ($reviews as $review): ?>
      <div class="review-box">
        <div class="review-header">
        <?php if (!empty($review['profile_pic']) && file_exists($review['profile_pic'])): ?>
          <img src="<?= htmlspecialchars($review['profile_pic']) ?>" alt="Profile Pic" class="review-profile-pic">
        <?php else: ?>
          <div class="blank-review-pic">No Image</div>
        <?php endif; ?>

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
>>>>>>> Stashed changes
