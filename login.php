<head>
    <title>Login</title>
    <?php include 'inc/head.inc.php'; ?>
    
    <body>
        <?php include 'inc/nav.inc.php'; ?>
        <header class="header-title">
            <h1>Login</h1>
        </header>

        
        <!-- Main Content (Auth Form) -->
        <main class="auth-form-container">
            <form id="login-form">
            <div class="input-group">
                <label for="login-email">Email</label>
                <input type="email" id="login-email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="login-password">Password</label>
                <input type="password" id="login-password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="submit-btn">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
        </main>
        <?php include 'inc/footer.inc.php'; ?>
    </body>
    
    
</head>