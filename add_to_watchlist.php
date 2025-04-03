<?php
session_start();
require_once 'sql/queries.php';
header('Content-Type: application/json');

// Check if user is logged in
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

    // Check if already in watchlist
    $check = $conn->prepare("SELECT 1 FROM watchlist WHERE user_id = ? AND movie_id = ?");
    $check->bind_param("ii", $userId, $movieId);
    $check->execute();
    $result = $check->get_result();

    if ($result && $result->num_rows === 0) {
        // Add to watchlist (allow any movie_id, even if not in DB)
        $insert = $conn->prepare("INSERT INTO watchlist (user_id, movie_id, created_at) VALUES (?, ?, NOW())");

        if (!$insert) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "❌ Prepare failed: " . $conn->error
            ]);
            exit;
        }

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
                "message" => "❌ Insert failed: " . $insert->error
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
