<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to like movies.']);
    exit;
}

require_once 'sql/queries.php'; // adjust path
$conn = connectToDB();

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['movie_id'] ?? null;

if (!$movie_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID.']);
    exit;
}

// Check if already liked
$stmt = $conn->prepare("SELECT * FROM movie_likes WHERE user_id = ? AND movie_id = ?");
$stmt->bind_param("ii", $user_id, $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Already liked, so remove it
    $stmt = $conn->prepare("DELETE FROM movie_likes WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'liked' => false, 'message' => 'Removed from liked movies.']);
} else {
    // Not liked yet, so insert
    $stmt = $conn->prepare("INSERT INTO movie_likes (user_id, movie_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'liked' => true, 'message' => 'Added to liked movies!']);
}
$conn->close();
?>