<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Browse by Category</title>
  <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
  <script src="js/script.js?v=<?= time() ?>" defer></script>
  <script src="js/category.js?v=<?= time() ?>" defer></script>
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <main class="category-container">
    <header>
      <h1>Browse Movies by Genre</h1>
      <div class="category-controls">
        <select id="genre-select">
          <option value="28">Action</option>
          <option value="35">Comedy</option>
          <option value="18">Drama</option>
          <option value="27">Horror</option>
          <option value="10749">Romance</option>
          <option value="878">Sci-Fi</option>
          <option value="16">Animation</option>
          <option value="12">Adventure</option>
          <option value="53">Thriller</option>
        </select>

    <div id="movie-grid" class="movie-grid movie-list">
      <!-- JavaScript will populate movie cards here -->
    </div>
  </main>

  <?php include 'inc/footer.inc.php'; ?>
</body>
</html>
