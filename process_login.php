<?php
    session_start();
    require_once 'sql/queries.php';
    $email = $pwd = "";
    $errorMsg = "";
    $success = true;

    function sanitize_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(empty($_POST['email'])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.<br>";
            $success = false;
        }
    }

    if (empty($_POST['pwd'])) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } else {
        $pwd = sanitize_input($_POST['pwd']);
    }

    if ($success && $_SERVER["REQUEST_METHOD"] == "POST"){
        $user = getUserDetailsByEmail($email);

        if($user->num_rows > 0){
            $user = $user->fetch_assoc();
            if(password_verify($pwd, $user["pwd"])){
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];
                header("Location: index.php");
                exit();
            }else{
                header("Location: login.php?error=Invalid username or password");
                exit();
            }
        }
        header("Location: login.php?error=Invalid username or password");
        exit();
    }
    
?>
<?php include 'inc/footer.inc.php'; ?>
