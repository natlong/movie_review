<?php
session_start();
header('Content-Type: application/json');
require_once 'sql/queries.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to like movies.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = isset($_POST['movie_id']) && is_numeric($_POST['movie_id']) ? intval($_POST['movie_id']) : null;

if (!$movie_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID.']);
    exit;
}

$conn = connectToDB();

// ✅ 1. Check if movie exists in DB
$movie = getAllMoviesByMovieId($movie_id);
if (!$movie) {
    // ❌ Not in DB → fetch from TMDb
    $tmdb = fetchMovieDetails($movie_id);
    if (!$tmdb || !isset($tmdb['movieData'])) {
        echo json_encode(['success' => false, 'message' => '❌ Could not fetch movie from API.']);
        exit;
    }

    $data = $tmdb['movieData'];

    // ✅ Insert movie into DB
    $inserted = insertMovie(
        $movie_id,
        $data['title'],
        $data['overview'],
        $data['genres'],
        $data['rating']
    );

    if (!$inserted) {
        echo json_encode(['success' => false, 'message' => '❌ Failed to save movie to local database.']);
        exit;
    }
}

// ✅ 2. Check if already liked
$stmt = $conn->prepare("SELECT * FROM movie_likes WHERE user_id = ? AND movie_id = ?");
$stmt->bind_param("ii", $user_id, $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Already liked → remove it
    $stmt = $conn->prepare("DELETE FROM movie_likes WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'liked' => false, 'message' => '❤️ Removed from liked movies.']);
} else {
    // Not liked → insert
    $stmt = $conn->prepare("INSERT INTO movie_likes (user_id, movie_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'liked' => true, 'message' => '💖 Added to liked movies!']);
}

$stmt->close();
$conn->close();
