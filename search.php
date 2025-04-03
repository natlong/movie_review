<?php
session_start();
require_once 'sql/queries.php';

$searchQuery = $_GET['query'] ?? '';
$searchResults = [];

if ($searchQuery) {
    $searchResults = searchMovies($searchQuery); // this returns both API + DB movies
}

function getPosterURL($posterPath) {
    if (!$posterPath) {
        return 'images/image_not_found.jpg';
    }

    // Check if it's a TMDb path (starts with "/") or our DB (starts with "imgs/" or "uploads/")
    if (str_starts_with($posterPath, '/')) {
        return 'https://image.tmdb.org/t/p/w200' . $posterPath;
    } elseif (str_starts_with($posterPath, 'imgs/') || str_starts_with($posterPath, 'uploads/')) {
        return $posterPath;
    }

    return 'images/image_not_found.jpg';
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
          <img src="<?= getPosterURL($movie['poster_path'] ?? $movie['img_link'] ?? null) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
          <div class="search-info">
            <h3><?= htmlspecialchars($movie['title']) ?></h3>
            <p><?= $movie['release_date'] ?? 'N/A' ?></p>
            <div class="rating">⭐ <?= number_format($movie['vote_average'] ?? $movie['ratings'] ?? 0, 1) ?></div>
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
