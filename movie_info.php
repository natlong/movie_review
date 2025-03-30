<?php
require_once 'sql/queries.php';
$movieId = $_GET['id'] ?? null;
if (!$movieId) die("Movie ID not provided.");
$response = fetchMovieDetails($movieId);

if (!$response) {
    die("Movie not found.");
}

$movieData = $response['movieData'];
$movieExists = $response['movieExists'];

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
        <?php if ($movieExists): ?>
          <a href="write_review.php?id=<?= $movieId ?>"><button class="btn-review">✍️ Write a Review</button></a>
        <?php else: ?>
          <a href="insert.php?id=<?= $movieId ?>"><button class="btn-review">📬 Request to Write a Review</button></a>
        <?php endif; ?>
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
        <a href="movie_info.php?id=<?= $rec['id'] ?>" class="movie-link"style="text-decoration: none;">
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
