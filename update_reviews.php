<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'sql/queries.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $reviewId = $_POST['review_id'] ?? null;
    $reviewText = trim($_POST['review_text'] ?? '');
    $rating = floatval($_POST['rating'] ?? 0);

    if (!$reviewId || $reviewText === '' || $rating < 0 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $existingReview = getReviewById($reviewId);
    if (!$existingReview || $existingReview['user_id'] != $userId) {
        echo json_encode(['status' => 'error', 'message' => 'Review not found or unauthorized']);
        exit;
    }

    $updated = updateReview($reviewId, $reviewText, $rating);

    if ($updated) {
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Review updated.',
            'updated_text' => nl2br(htmlspecialchars($reviewText)),
            'updated_rating' => number_format($rating, 1)
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => '❌ Failed to update review.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
