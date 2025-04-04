<?php
session_start();
require_once 'sql/queries.php';

header('Content-Type: application/json');

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

$reviewId = $_POST['review_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$reviewId || !is_numeric($reviewId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid review ID.']);
    exit;
}

// Confirm the review belongs to the user
$review = getReviewById($reviewId);
if (!$review || $review['user_id'] != $userId) {
    echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this review.']);
    exit;
}

// Proceed to delete
$conn = connectToDB();
$stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ? AND user_id = ?");
$stmt->bind_param("ii", $reviewId, $userId);
$success = $stmt->execute();
$stmt->close();
$conn->close();

// Respond with JSON
if ($success) {
    echo json_encode(['success' => true, 'message' => '✅ Review deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Failed to delete review.']);
}
?>
