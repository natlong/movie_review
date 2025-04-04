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

$upload_message = '';
$default_image = 'images/default-profile.png';
$profile_image = ''; // Start blank

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
$profile_path = $user_profile['profile_pic'] ?? '';

if (!empty($profile_path) && file_exists($profile_path)) {
    $profile_image = $profile_path;
} else {
    $profile_image = ''; // empty, show placeholder box
}

$watchlist = getMovieFromWatchListByUserId($userId);
$hasWatchlist = is_array($watchlist) && count($watchlist) > 0;

$likeList = getMovieListFromLikedListByUserId($userId);
$hasLikedList = is_array($likeList) && count($likeList) > 0;

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
  <script src="js/script.js?v=<?= time() ?>" defer></script>
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <div class="profile-container">
    <div class="profile-details">
      <div class="profile-image-container">
        <?php if (!empty($profile_image)): ?>
          <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Picture" class="profile-image">
        <?php else: ?>
          <div class="blank-profile-box">No Image</div>
        <?php endif; ?>
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
            <div class="movie-card">
              <img src="<?= htmlspecialchars($data['poster']) ?>" alt="<?= htmlspecialchars($data['title']) ?>" class="movie-img">
              <h3><?= htmlspecialchars($data['title']) ?></h3>
              <p class="movie-info"><?= htmlspecialchars($data['release']) ?> • ⭐ <?= htmlspecialchars($data['rating']) ?></p>
              <a href="movie_info.php?id=<?= $movie['id'] ?>" class="btn">View Details</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="likes-container">
      <h3>Movies you Liked</h3>
      <?php if (!$hasLikedList): ?>
        <p>No movies liked yet.</p>
      <?php else: ?>
        <div class="scroll-container" id="movies-scroll">
          <?php foreach ($likeList as $item): ?>
            <?php
              $movieId = $item['movie_id'];
              if (!$movieId) continue;
              $movieDetails = fetchMovieDetails($movieId);
              if (!$movieDetails || !isset($movieDetails['movieData'])) continue;
              $data = $movieDetails['movieData'];
            ?>
            <div class="movie-card">
              <img src="<?= htmlspecialchars($data['poster']) ?>" alt="<?= htmlspecialchars($data['title']) ?>" class="movie-img">
              <h3><?= htmlspecialchars($data['title']) ?></h3>
              <p class="movie-info"><?= htmlspecialchars($data['release']) ?> • ⭐ <?= htmlspecialchars($data['rating']) ?></p>
              <a href="movie_info.php?id=<?= $movie['id'] ?>" class="btn">View Details</a>
            </div>
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
              $reviewText = htmlspecialchars($review['review'] ?? '');
              $reviewDate = isset($review['created_at']) ? date('F j, Y', strtotime($review['created_at'])) : 'Unknown';
            ?>
            <div class="review-box" data-review-id="<?= $review['review_id'] ?>">
              <div class="review-header">
                <h4><?= htmlspecialchars($movieTitle) ?></h4>
                <span class="review-rating">⭐ <span class="rating-display"><?= $movieRating ?></span></span>
              </div>
              <p class="review-text" id="review-text-<?= $review['review_id'] ?>"><?= nl2br($reviewText) ?></p>
              <div class="review-footer">
                <small>Posted on <?= $reviewDate ?></small>
                <br><br>
                <button class="btn edit-review-btn" data-review-id="<?= $review['review_id'] ?>">✏️ Edit</button>
                <button type="button" class="btn delete-review-btn" data-review-id="<?= $review['review_id'] ?>">🗑️ Delete</button>
              </div>
              <form class="edit-review-form" id="edit-form-<?= $review['review_id'] ?>" data-review-id="<?= $review['review_id'] ?>" style="display: none;">
                <textarea name="review_text" required><?= htmlspecialchars($review['review']) ?></textarea>
                <input type="number" name="rating" min="0" max="5" step="0.1" value="<?= $movieRating ?>">
                <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                <button type="submit" class="btn">💾 Save</button>
                <button type="button" class="btn cancel-btn">❌ Cancel</button>
                <div class="edit-result"></div>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php include 'inc/footer.inc.php'; ?>
</body>
</html>
