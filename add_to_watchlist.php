<?php
session_start();
require_once 'sql/queries.php';

header('Content-Type: application/json'); // JSON response only

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Please log in to add to watchlist."
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['movie_id'])) {
    $userId = $_SESSION['user_id'];
    $movieId = intval($_POST['movie_id']);

    $conn = connectToDB();

    // Check if already exists
    $check = $conn->prepare("SELECT 1 FROM watchlist WHERE user_id = ? AND movie_id = ?");
    $check->bind_param("ii", $userId, $movieId);
    $check->execute();
    $result = $check->get_result();

    if ($result && $result->num_rows === 0) {
        // Insert new
        $insert = $conn->prepare("INSERT INTO watchlist (user_id, movie_id, created_at) VALUES (?, ?, NOW())");
        $insert->bind_param("ii", $userId, $movieId);

        if ($insert->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "✅ Movie added to your watchlist."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "❌ Failed to add movie. Try again later."
            ]);
        }
        $insert->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "⚠️ Movie already in your watchlist."
        ]);
    }

    $check->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request – movie ID missing."
    ]);
}
?>
