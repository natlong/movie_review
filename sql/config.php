<?php
    $file_path = "/var/www/private/db-config.ini";
    if(!file_exists($file_path)){
        $errorMsg = "Failed to read database config file, does not exist.";
        $success = false;
    }
    if(!is_readable($file_path)){
        $errorMsg = "Failed to read database config file, not readable.";
        $success = false;
    }

    $config = parse_ini_file($file_path); 
    if (!$config) 
    { 
        $errorMsg = "Failed to read database config file."; 
        $success = false; 
    } 
    else{
        $conn = new mysqli( 
            $config['servername'], 
            $config['username'], 
            $config['password'], 
            $config['dbname'] 
        ); 

        // Check connection 
        if ($conn->connect_error) 
        { 
            $errorMsg = "Connection failed: " . $conn->connect_error; 
            $success = false; 
        } 
    }
?>

<!-- $servername = "localhost";
        $username = "root";
        $password = "Student@s1t";
        $dbname = "movie_review_db"; -->
<!-- 
<?php
    $servername = "localhost";
    $username = "sqldev";
    $password = "sqldev@s1t";
    $dbname = "movie_review_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?> -->
