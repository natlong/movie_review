<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'sql/queries.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please log in first."));
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Handle profile picture upload
$upload_message = '';
$profile_image = 'images/default-profile.png'; // Default image

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $upload_dir = 'uploads/profile_pics/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file = $_FILES['profile_picture'];
    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];
    $file_error = $file['error'];
    $file_size = $file['size'];

    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    $new_filename = $userId . '_' . time() . '.' . $file_ext;
    $target_file = $upload_dir . $new_filename;

    if ($file_error === 0) {
        if ($file_size <= 5000000) {
            if (in_array($file_ext, $allowed_extensions)) {
                if (move_uploaded_file($tmp_name, $target_file)) {
                    if (updateUserProfilePic($userId, $target_file)) {
                        $_SESSION['profile_pic'] = $target_file;
                        $upload_message = "Profile picture updated successfully!";
                    } else {
                        $upload_message = "Error updating profile picture in database.";
                    }
                } else {
                    $upload_message = "Failed to upload file.";
                }
            } else {
                $upload_message = "Only JPG, JPEG, PNG and GIF files are allowed.";
            }
        } else {
            $upload_message = "File is too large. Maximum size is 5MB.";
        }
    } else {
        $upload_message = "Error uploading file: " . $file_error;
    }
}

$user_profile = getUserProfile($userId);
if ($user_profile && isset($user_profile['profile_pic']) && !empty($user_profile['profile_pic'])) {
    $profile_image = $user_profile['profile_pic'];
}

$watchlist = getMovieFromWatchListByUserId($userId);
$hasWatchlist = is_array($watchlist) && count($watchlist) > 0;

$reviews = getReviewsByUserId($userId);
$hasReviews = is_array($reviews) && count($reviews) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Page</title>
  <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <div class="profile-container">
    <div class="profile-details">
      <div class="profile-image-container">
        <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Picture" class="profile-image">
      </div>
      <div class="profile-info">
        <h2><?= htmlspecialchars($username) ?></h2>
        <p>Email: <?= htmlspecialchars($email) ?></p>

        <form class="profile-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
          <input type="file" name="profile_picture" accept="image/*">
          <button type="submit" class="btn">Upload Profile Picture</button>
        </form>

        <?php if (!empty($upload_message)): ?>
          <div class="upload-status <?= strpos($upload_message, 'successfully') !== false ? 'success' : 'error' ?>">
            <?= $upload_message ?>
          </div>
        <?php endif; ?>

        <a href="change_password.php" class="btn">Change Password</a>
      </div>
    </div>

    <div class="watchlist-container">
      <h3>Your Watchlist</h3>

      <?php if (!$hasWatchlist): ?>
        <p>No watchlist yet.</p>
      <?php else: ?>
        <div class="scroll-container" id="movies-scroll">
          <?php foreach ($watchlist as $item): ?>
            <?php
              $movieId = $item['movie_id'];
              if (!$movieId) continue;

              $movieDetails = fetchMovieDetails($movieId);
              if (!$movieDetails || !isset($movieDetails['movieData'])) continue;

              $data = $movieDetails['movieData'];
            ?>
            <a href="movie_info.php?id=<?= $movieId ?>" class="movie-link">
              <div class="movie-card">
                <img src="<?= htmlspecialchars($data['poster']) ?>" alt="<?= htmlspecialchars($data['title']) ?>" class="movie-img">
                <h3><?= htmlspecialchars($data['title']) ?></h3>
                <p class="movie-info"><?= htmlspecialchars($data['release']) ?> • ⭐ <?= htmlspecialchars($data['rating']) ?></p>
                <button class="btn">+ Watchlist</button>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="reviews-container">
      <h3>Your Movie Reviews</h3>

      <?php if (!$hasReviews): ?>
        <p>No reviews yet. Go write one!</p>
      <?php else: ?>
        <div class="user-reviews-list">
          <?php foreach ($reviews as $review): ?>
            <?php
              $movieDetails = fetchMovieDetails($review['movie_id']);
              $movieData = $movieDetails['movieData'] ?? null;
              $movieTitle = $movieData['title'] ?? 'Unknown Movie';
              $movieRating = isset($review['rating']) ? number_format($review['rating'], 1) : '0.0';
              $reviewText = nl2br(htmlspecialchars($review['review'] ?? ''));
              $reviewDate = isset($review['created_at']) ? date('F j, Y', strtotime($review['created_at'])) : 'Unknown';
            ?>
            <div class="review-box" style="min-height: 200px; width: 400%;">
              <div class="review-header">
                <h4><?= htmlspecialchars($movieTitle) ?></h4>
                <span class="review-rating">⭐ <?= $movieRating ?></span>
              </div>
              <p class="review-text"><?= $reviewText ?></p>
              <div class="review-footer">
                <small>Posted on <?= $reviewDate ?></small>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php include 'inc/footer.inc.php'; ?>
</body>
</html>