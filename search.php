<?php
session_start();
require_once 'sql/queries.php';

$searchQuery = $_GET['query'] ?? '';
$searchResults = [];

if ($searchQuery) {
  $searchResults = searchMovies($searchQuery); // function from queries.php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search Results - MovieVerse</title>
  <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
  <link rel="stylesheet" href="css/search.css?v=<?= time() ?>">
  <script src="/js/script.js?v=<?= time() ?>" defer></script>
</head>
<body>

<?php include 'inc/nav.inc.php'; ?>

<main class="container">
  <h2 class="text-white" style="margin-top: 20px; text-align: center;">
    Search Results for "<span style="color: gold;"><?= htmlspecialchars($searchQuery) ?></span>"
  </h2>

  <?php if (!empty($searchResults)): ?>
    <div class="search-results">
      <?php foreach ($searchResults as $movie): ?>
        <a href="movie_info.php?id=<?= $movie['id'] ?>" class="search-result-item">
          <img src="<?= $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w200' . $movie['poster_path'] : 'images/image_not_found.jpg' ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
          <div class="search-info">
            <h3><?= htmlspecialchars($movie['title']) ?></h3>
            <p><?= $movie['release_date'] ?? 'TBA' ?></p>
            <div class="rating">⭐ <?= number_format($movie['vote_average'], 1) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-white" style="text-align: center; margin-top: 2rem;">
      No results found for "<strong><?= htmlspecialchars($searchQuery) ?></strong>"
    </p>
  <?php endif; ?>
</main>

<?php include 'inc/footer.inc.php'; ?>
</body>
</html>
