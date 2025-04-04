<?php
session_start();
include 'inc/head.inc.php';
include 'inc/nav.inc.php';
require_once 'sql/queries.php';

$topMovies = fetchTopRatedMovies(250);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Top 250 Movies - MovieVerse</title>
  <link rel="stylesheet" href="css/top_movie.css?v=<?= time() ?>">
  <script src="js/script.js?v=<?= time() ?>" defer></script>
</head>
<body>

<main class="container">
  <h2 class="text-center text-white mb-4" style="text-align: center; color: white; margin-top: 20px;">
    🎬 Top 250 Movies
  </h2>

  <div class="movie-grid movie-list">
    <?php foreach ($topMovies as $index => $movie): ?>
      <div class="movie-card"
           data-rating="<?= $movie['vote_average'] ?>"
           data-year="<?= substr($movie['release_date'], 0, 4) ?>">

        <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" 
             alt="<?= htmlspecialchars($movie['title']) ?>">

        <div class="movie-details">
          <h3><?= $index + 1 ?>. <?= htmlspecialchars($movie['title']) ?></h3>
          <p class="meta"><?= $movie['release_date'] ?> &nbsp; ⭐ <?= number_format($movie['vote_average'], 1) ?></p>
          <a href="movie_info.php?id=<?= $movie['id'] ?>" class="btn">View Details</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<?php include 'inc/footer.inc.php'; ?>
</body>
</html>
