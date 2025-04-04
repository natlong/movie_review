<?php
session_start();
require_once 'sql/queries.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$reviewId = $_POST['review_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$reviewId) {
    echo json_encode(['status' => 'error', 'message' => 'Review ID missing']);
    exit;
}

// Check ownership
$review = getReviewById($reviewId);
if (!$review || $review['user_id'] != $userId) {
    echo json_encode(['status' => 'error', 'message' => 'Not allowed']);
    exit;
}

// Delete
$conn = connectToDB();
$stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ? AND user_id = ?");
$stmt->bind_param("ii", $reviewId, $userId);
$success = $stmt->execute();

$stmt->close();
$conn->close();

if ($success) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Deletion failed']);
}
?>
