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
