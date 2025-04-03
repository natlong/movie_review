<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // User is already logged in, redirect them away
    header("Location: index.php?message=already_logged_in");
    exit();
}
?>

<head>
    <title>Login</title>
    <?php include 'inc/head.inc.php'; ?>
</head>

<body>
    <?php include 'inc/nav.inc.php'; ?>
    <header class="header-title">
        <h1>Login</h1>
    </header>

    
    <!-- Main Content (Auth Form) -->
    <main class="auth-form-container">
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message" style="color: red; margin-bottom: 1em;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form id="login-form" action="process_login.php" method="post">
        
        <div class="input-group">
            <label for="login-email">Email</label>
            <input type="email" id="login-email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-group">
            <label for="login-password">Password</label>
            <input type="password" id="login-password" name="pwd" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="submit-btn">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </main>
    <?php include 'inc/footer.inc.php'; ?>
</body>
    
    
