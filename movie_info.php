<?php
$movieId = $_GET['id'] ?? null;
if (!$movieId) die("Movie ID not provided.");

$apiKey = '0898e5d05464d2b33011428dac1eee0f';

// Fetch movie details
$details = json_decode(file_get_contents("https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=videos,credits,recommendations"), true);
if (!$details || isset($details['status_code'])) die("Movie not found.");

$title = htmlspecialchars($details['title']);
$poster = "https://image.tmdb.org/t/p/w500" . $details['poster_path'];
$overview = $details['overview'];
$rating = number_format($details['vote_average'], 2);
$release = $details['release_date'];
$genres = implode(', ', array_column($details['genres'], 'name'));
$videoKey = $details['videos']['results'][0]['key'] ?? null;
$cast = array_slice($details['credits']['cast'], 0, 5);
$recommendations = array_slice($details['recommendations']['results'], 0, 5);
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $title ?> - MovieVerse</title>
  <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
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
