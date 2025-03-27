<head>
    <title>Sign Up</title>
    <?php include 'inc/head.inc.php'; ?>

    <body>
        <header class="header-title">
            <h1>Sign Up</h1>
        </header>
        <main class="auth-form-container">
            <form id="signup-form">
            <div class="input-group">
                <label for="signup-name">Full Name</label>
                <input type="text" id="signup-name" placeholder="Enter your full name" required>
            </div>
            <div class="input-group">
                <label for="signup-email">Email</label>
                <input type="email" id="signup-email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="signup-password">Password</label>
                <input type="password" id="signup-password" placeholder="Create a password" required>
            </div>
            <button type="submit" class="submit-btn">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.html">Login</a></p>
        </main>
        <?php include 'inc/footer.inc.php'; ?>
    </body>
</head>