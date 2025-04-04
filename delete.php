<<<<<<< Updated upstream
<?php
session_start();
require_once 'sql/queries.php';

$conn = connectToDB();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM requests WHERE idrequests = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Request successfully deleted.";
        header("Location: insert.php?message=" . urlencode($message));
        exit();
    } else {
        $error = "Failed to delete request.";
        header("Location: insert.php?error=" . urlencode($error));
        exit();
    }

    $stmt->close();
} else {
    $error = "Invalid request ID.";
    header("Location: insert.php?error=" . urlencode($error));
    exit();
}

$conn->close();
?>
=======
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
>>>>>>> Stashed changes
