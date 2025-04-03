<?php
    session_start();
    require_once 'sql/queries.php'; // or the correct path to queries.php
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
    
    // Initialize variables
    $name = $email = $errorMsg = "";
    $success = true;
    

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
    $confirm_pwd = $_POST['confirm_pwd'] ?? "";

    if(empty($pwd || empty($confirm_pwd))) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } else {
        if ($pwd !== $confirm_pwd) {
            $errorMsg .= "Passwords do not match.";
            $success = false;
        }
    }
    
    if ($success && $_SERVER["REQUEST_METHOD"] == "POST"){
        // Hash password securely
        $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $result = registerUser($name, $email, $hashed_pwd);

        if(!$result){
            $errorMsg = "❌ Failed to save member to database.";
            $success = false;
        }
    }    
    if ($success) {
        $_SESSION["user_id"] = $result; 
        $_SESSION["username"] = $name;
        $_SESSION["role"] = "user"; // Default role
        header("Location: signup.php?success=".urlencode("Account created successfully! You will be redirected to the profile page."));
        exit();
    }else{
        // Redirect to signup page with error message
        header("Location: signup.php?error=" . urlencode($errorMsg));
        exit();
    }
    
    ?>
    
    
    <?php include 'inc/footer.inc.php'; ?>
</body>



