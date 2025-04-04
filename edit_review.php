<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

session_start();
require_once 'sql/queries.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$userId = $_SESSION['user_id'];
$reviewId = $_POST['review_id'] ?? null;
$reviewText = trim($_POST['review_text'] ?? '');
$rating = floatval($_POST['rating'] ?? 0);

if (!$reviewId || $reviewText === '' || $rating < 0 || $rating > 5) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    exit;
}

// Optional: Check that the review belongs to the user
$existing = getReviewById($reviewId);
if (!$existing || $existing['user_id'] != $userId) {
    echo json_encode(['status' => 'error', 'message' => 'Review not found or access denied.']);
    exit;
}

$success = updateReview($reviewId, $reviewText, $rating);

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Review updated.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update review.']);
}
