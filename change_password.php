<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
  <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <main class="container mt-5">
    <h2 class="text-white mb-4">Change Password</h2>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php elseif (isset($_GET['message'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <form action="process_password_change.php" method="POST" style="max-width: 400px; margin: 0 auto;">
      <div class="form-group">
        <label for="old_password" class="text-white">Current Password</label>
        <input type="password" class="form-control" name="old_password" required>
      </div>

      <div class="form-group">
        <label for="new_password" class="text-white">New Password</label>
        <input type="password" class="form-control" name="new_password" required>
      </div>

      <div class="form-group">
        <label for="confirm_password" class="text-white">Confirm New Password</label>
        <input type="password" class="form-control" name="confirm_password" required>
      </div>

      <button type="submit" class="btn btn-warning mt-3">Update Password</button>
    </form>
  </main>

  <?php include 'inc/footer.inc.php'; ?>
</body>
</html>
