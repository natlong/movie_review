<?php
require_once 'sql/queries.php';
  session_start();

  if (!isset($_SESSION['user_id'])) {
      header("Location: login.php?error=" . urlencode("Please log in first."));
      exit();
  }
   // Include your database queries file
  $watchlistMovies = getMovieFromWatchListByUserId($_SESSION['user_id']); // Fetch movies
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Page</title>
  <?php include 'inc/head.inc.php'; ?>
  <!-- Ensure Bootstrap CSS is loaded in inc/head.inc.php or here -->
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>
    <div class="profile-container">
        <!-- Profile Details -->
        <div class="profile-details">
            <div class="profile-image-container">
                <img src="path/to/profile-pic.jpg" alt="Profile Picture" class="profile-image">
            </div>
            <div class="profile-info">
                <h2><?= $_SESSION['username']; ?></h2>
                <p>Email: <?= $_SESSION['email']; ?></p>
                <a href="change_password.php" class="btn">Change Password</a>
            </div>
        </div>

        <!-- Watchlist Section -->
        <div class="watchlist-container">
            <h3>Your Watchlist</h3>
            <div class="scroll-container" id="movies-scroll">
              <?php foreach ($watchlistMovies as $movie): ?>
                <a href="movie_info.php?id=<?= $movie['id'] ?>" class="movie-link" style="text-decoration: none;">
                    <div class="movie-card">
                    <img src="<?= $movie['poster_path'] 
                        ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] 
                        : 'images/image_not_found.jpg' ?>" 
                        alt="<?= $movie['title'] ?>" 
                        class="movie-img">
                        <!-- <h3><?= $movie['title'] ?></h3> -->
                        <h3 title="<?= htmlspecialchars($movie['title']) ?>">
                            <?= htmlspecialchars($movie['title']) ?>
                        </h3>
                        <p class="movie-info"><?= $movie['release_date'] ?> • ⭐ <?= number_format($movie['vote_average'], 2) ?></p>
                        <button class="btn">+ Watchlist</button>
                    </div>
                </a>
            <?php endforeach; ?>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-container">
            <h3>Your Movie Reviews</h3>
            <div class="reviews-buttons">
                <?php
                    // Check if the user has written any reviews
                    $userReviews = getReviewsByUserId($_SESSION['user_id']); // Function to get reviews
                    if (count($userReviews) > 0) {
                        echo "<a href='view_reviews.php' class='btn'>View Your Reviews</a>";
                    } else {
                        echo "<a href='write_review.php' class='btn'>Write a Review</a>";
                    }
                ?>
            </div>
        </div>
    </div>

    <?php include 'inc/footer.inc.php'; ?>
</body>
</html>
