<?php
session_start();
header('Content-Type: application/json');

require_once 'sql/queries.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = isset($_POST['movie_id']) && is_numeric($_POST['movie_id']) ? intval($_POST['movie_id']) : null;

if (!$movie_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID.']);
    exit;
}

// ✅ Step 1: Ensure movie exists in DB
function ensureMovieInDatabase($movie_id) {
    $movie = getAllMoviesByMovieId($movie_id);
    if ($movie) return true;

    $tmdb = fetchMovieDetails($movie_id);
    if (!$tmdb || !isset($tmdb['movieData'])) {
        return false;
    }

    $data = $tmdb['movieData'];
    return insertMovie(
        $movie_id,
        $data['title'],
        $data['overview'],
        $data['genres'],
        $data['rating']
    );
}

if (!ensureMovieInDatabase($movie_id)) {
    echo json_encode(['success' => false, 'message' => '❌ Failed to fetch movie info from TMDb.']);
    exit;
}

// ✅ Step 2: Check for duplicates
$existing = getMovieFromWatchListByUserId($user_id);
$existingIds = array_column($existing, 'movie_id');

if (in_array($movie_id, $existingIds)) {
    echo json_encode(['success' => false, 'message' => '⚠️ Movie already in your watchlist.']);
    exit;
}

// ✅ Step 3: Add to watchlist
$conn = connectToDB();
$stmt = $conn->prepare("INSERT INTO watchlist (user_id, movie_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $movie_id);
$success = $stmt->execute();

$stmt->close();
$conn->close();

echo json_encode([
    'success' => $success,
    'message' => $success ? '✅ Movie added to watchlist!' : '❌ Failed to add movie to watchlist.'
]);
?>
