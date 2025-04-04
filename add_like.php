<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once 'sql/queries.php'; // Adjust path if necessary

$conn = connectToDB();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to like movies.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['movie_id'] ?? null;

if (!$movie_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID.']);
    exit;
}

// --- Check if movie exists in database ---
$movie = getAllMoviesByMovieId($movie_id);

if (!$movie) {
    $tmdbData = fetchMovieDetails($movie_id);

    if (!$tmdbData) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch movie details.']);
        exit;
    }

    $insertSuccess = insertMovie(
        $movie_id,
        $tmdbData['title'],
        $tmdbData['overview'],
        $tmdbData['genres'],
        $tmdbData['rating']
    );

    if (!$insertSuccess) {
        $error = "INSERT MOVIE ERROR: " . $conn->error;
        file_put_contents('error_log.txt', $error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Error inserting movie into DB.']);
        exit;
    }
}

// --- Check if already liked ---
$stmt = $conn->prepare("SELECT * FROM movie_likes WHERE user_id = ? AND movie_id = ?");
if (!$stmt) {
    $error = "PREPARE SELECT ERROR: " . $conn->error;
    file_put_contents('error_log.txt', $error . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Prepare failed.']);
    exit;
}
$stmt->bind_param("ii", $user_id, $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Already liked, remove it
    $stmt = $conn->prepare("DELETE FROM movie_likes WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $user_id, $movie_id);

    if (!$stmt->execute()) {
        $error = "DELETE LIKE ERROR: " . $stmt->error;
        file_put_contents('error_log.txt', $error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Failed to remove like.']);
        exit;
    }

    echo json_encode(['success' => true, 'liked' => false, 'message' => 'Removed from liked movies.']);
} else {
    // Not liked, insert it
    $stmt = $conn->prepare("INSERT INTO movie_likes (user_id, movie_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $movie_id);

    if (!$stmt->execute()) {
        $error = "INSERT LIKE ERROR: " . $stmt->error;
        file_put_contents('error_log.txt', $error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Failed to add like.']);
        exit;
    }

    echo json_encode(['success' => true, 'liked' => true, 'message' => 'Added to liked movies!']);
}

$conn->close();
?>
