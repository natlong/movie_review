<head>
    <title>Process Register</title>
    <?php include 'inc/head.inc.php'; ?>
</head>
<body>
    <?php include 'inc/nav.inc.php'; ?>
    
    <?php
    $file_path = "/var/www/private/db-config.ini";
    
    if (!file_exists($file_path)) {
        die("❌ Database config file does NOT exist!");
    }
    if (!is_readable($file_path)) {
        die("❌ Database config file is NOT readable!");
    }
    
    // Initialize variables
    $name = $email = $errorMsg = "";
    $success = true;
    
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
    
    // Validate email
    if (empty($_POST['email'])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.<br>";
            $success = false;
        }
    }
    
    // Validate last name
    if (empty($_POST['name'])) {
        $errorMsg .= "name is required.<br>";
        $success = false;
    } else {
        $name = sanitize_input($_POST['name']);
    }
    
    // Validate passwords
    $pwd = $_POST['pwd'] ?? "";
    
    // Hash password securely
    $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
    
    function saveMemberToDB() {
        global $name, $email, $hashed_pwd, $errorMsg, $success;
    
        $config = parse_ini_file('/var/www/private/db-config.ini');
        if (!$config) {
            die("❌ Failed to read database config file.");
        }
    
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    
        if ($conn->connect_error) {
            die("❌ Connection failed: " . $conn->connect_error);
        }
    
        $result = registerUser($name, $email, $hashed_pwd);
        if (!$result) {
            $errorMsg = "❌ Failed to save member to database.";
            $success = false;
        }
        $conn->close();
    }
    
    if ($success) {
        saveMemberToDB();
        echo '<div style="margin: 50px auto; width: 50%; text-align: left;">
                <h2>Your registration is successful!</h2>
                <h4> Thank you for signing up, '. htmlspecialchars($name) .'.</h4>
                <a href="login.php" style="text-decoration: none;">
                    <button style="background-color: #5cb85c; color: white; border: none; padding: 10px 15px; font-size: 16px; border-radius: 5px; cursor: pointer; display: inline-block; margin-top: 15px;">
                        Log-in
                    </button>
                </a>
            </div>';
    } else {
        echo '<div style="margin: 50px auto; width: 50%; text-align: left;">
                <h2>Oops!</h2>
                <h4>The following errors were detected:</h4>
                <p>' . htmlspecialchars($errorMsg) . '</p>
                <a href="register.php" style="text-decoration: none;">
                    <button style="background-color: #d9534f; color: white; border: none; padding: 10px 15px; font-size: 16px; border-radius: 5px; cursor: pointer; display: inline-block; margin-top: 15px;">
                        Return to Sign Up
                    </button>
                </a>
            </div>';
    }
    ?>
    
    <?php include 'inc/footer.inc.php'; ?>
</body>



