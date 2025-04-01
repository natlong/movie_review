<?php
session_start();
?>


<head>
    <title>Sign Up</title>
    <?php include 'inc/head.inc.php'; ?>

    <body>
        <?php include 'inc/nav.inc.php'; ?>
        <header class="header-title">
            <h1>Sign Up</h1>
        </header>
        <main class="auth-form-container">
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message" style="color:red; margin-bottom: 1em;">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message" style="color:green; margin-bottom: 1em;">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>

            <script>
                setTimeout(() => {
                    window.location.href = "profile.php";
                }, 2000); // Redirect after 2 seconds
            </script>
        <?php endif; ?>

            <form id="signup-form" action="process_signup.php" method="POST">
            <div class="input-group">
                <label for="signup-name">Full Name</label>
                <input type="text" id="signup-name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="input-group">
                <label for="signup-email">Email</label>
                <input type="email" id="signup-email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="signup-password">Password</label>
                <input type="password" id="signup-password" name="pwd" placeholder="Create a password" required>
            </div>
            <div class="input-group">
                <label for="signup-confirm-password">Confirm Password</label>
                <input type="confirm_password" id="signup-confirm-password" name="confirm_pwd" placeholder="Create a password" required>
            </div>
            <button type="submit" class="submit-btn">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </main>
        <?php include 'inc/footer.inc.php'; ?>
    </body>
</head>