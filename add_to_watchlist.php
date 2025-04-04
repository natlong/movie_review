<?php
session_start();
header('Content-Type: application/json');

require_once 'sql/queries.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate and sanitize movie_id input
$movie_id = isset($_POST['movie_id']) && is_numeric($_POST['movie_id']) ? intval($_POST['movie_id']) : null;
if (!$movie_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID.']);
    exit;
}

// Get all movies already in the user's watchlist
$existing = getMovieFromWatchListByUserId($user_id);
$existingIds = array_column($existing, 'movie_id');

// Check if the movie is already in the watchlist
if (in_array($movie_id, $existingIds)) {
    echo json_encode(['success' => false, 'message' => '⚠️ Movie already in your watchlist.']);
    exit;
}

$conn = connectToDB();

// Use a prepared statement to insert the movie into the watchlist
$stmt = $conn->prepare("INSERT INTO watchlist (user_id, movie_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $movie_id);
$success = $stmt->execute();

$stmt->close();
$conn->close();

if ($success) {
    echo json_encode(['success' => true, 'message' => '✅ Movie added to watchlist!']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Failed to add movie to watchlist.']);
}
?>
