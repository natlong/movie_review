<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
require_once 'sql/queries.php';
$trendingMovies = getTrendingMovies(10);
$featuredMovies = array_slice($trendingMovies, 0, 4); // Use top 4 trending as spotlight
$likedMovies = getAllMoviesFromLikedMoviesByUserId($user_id) ->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <title>MovieVerse</title>
    <?php include 'inc/head.inc.php'; ?>
    <script src="/js/script.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>
    <?php include 'inc/nav.inc.php'; ?>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (isset($_GET['message']) && $_GET['message'] === 'already_logged_in') {
        echo "<p class='alert'>You're already logged in!</p>";
    }
    
    ?>


    <!-- Spotlight Section -->
    <section id="featured-spotlight" class="spotlight-wrapper">
        <?php foreach ($featuredMovies as $i => $movie): ?>
            <div class="featured-card<?= $i === 0 ? ' active' : '' ?>">
                <img src="https://image.tmdb.org/t/p/original<?= $movie['backdrop_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <div class="spotlight-info">
                    <h2><?= htmlspecialchars($movie['title']) ?></h2>
                    <p><?= $movie['release_date'] ?> • ⭐ <?= number_format($movie['vote_average'], 1) ?></p>
                    <a href="movie_info.php?id=<?= $movie['id'] ?>" class="btn">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>

        <button class="arrow left-arrow" id="prev-featured">❮</button>
        <button class="arrow right-arrow" id="next-featured">❯</button>
    </section>

    <!-- Top 10 Movies -->
    <section class="movies-wrapper">
    <header class="header-title">🔥 Top 10 on MovieVerse this week</header>
    <div class="scroll-container">
        <?php foreach ($trendingMovies as $movie): ?>
            <div class="movie-card">
                <a href="movie_info.php?id=<?= $movie['id'] ?>" class="movie-link" style="text-decoration: none; border: none; outline: none;">
                    <img src="<?= $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : 'images/image_not_found.jpg' ?>" 
                         alt="<?= htmlspecialchars($movie['title']) ?>" class="movie-img">
                </a>
                <h3 title="<?= htmlspecialchars($movie['title']) ?>">
                    <?= htmlspecialchars($movie['title']) ?>
                </h3>
                <p class="movie-info">
                    <?= $movie['release_date'] ?> • ⭐ <?= number_format($movie['vote_average'], 2) ?>
                </p>
                <div class="button-container">
                    <button class="btn top10-watchlist-btn" onclick="addToWatchlist(<?= $movie['id'] ?>)">+ Watchlist</button>
                    <span class="like-icon" onclick="addToLikes(event, <?= $movie['id'] ?>, this)"><?= in_array($movie['id'], $likedMovies) ? '❤️' : '♡' ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

    <?php if ($user_id != null): ?>
    <?php
        $watchlistMovieIds = getMovieFromWatchListByUserId($user_id);
        $topGenres = getTopGenresFromWatchlist($user_id);
        $recommendedMovies = getTopMoviesByGenresExcluding($topGenres, $watchlistMovieIds, 10);
    ?>

    <?php if (!empty($recommendedMovies)): ?>
        <section class="recommended-container">
        <header class="header-title">🎯 Recommended for You</header>
        <div class="scroll-container">
            <?php foreach ($recommendedMovies as $movie): ?>
            <a href="movie_info.php?id=<?= $movie['id'] ?>" class="movie-link">
                <div class="movie-card">
                <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="movie-img">
                <h3><?= htmlspecialchars($movie['title']) ?></h3>
                <p class="movie-info"><?= $movie['release_date'] ?> • ⭐ <?= number_format($movie['vote_average'], 2) ?></p>
                <button class="btn">+ Watchlist</button>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        </section>

    <?php else: ?>
        <p style="text-align:center; color:#aaa;">No personalized recommendations yet. Add movies to your watchlist to get started!</p>
    <?php endif; ?>
<?php endif; ?>




    <!-- Movie Request CTA Section -->
<section class="request-section">
  <div class="request-inner">
    <h2>🎥 Can’t find your movie?</h2>
    <p>Send us a request and we’ll try to add it!</p>
    <a href="request.php" class="btn">Request a Movie</a>
  </div>
</section>


    <?php include 'inc/footer.inc.php'; ?>
</body>
</html>