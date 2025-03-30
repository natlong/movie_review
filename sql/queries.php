<?php
    function connectToDB(){
        $config = parse_ini_file('/var/www/private/db-config.ini');
        if (!$config) {
            die("❌ Failed to read database config file.");
        }
    
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    
        if ($conn->connect_error) {
            die("❌ Connection failed: " . $conn->connect_error);
        
        }
        return $conn;
    }

    //User Details Queries
    function getAllUsers(){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT user_id, username, email, password, profile_pic, role, created_at FROM users");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $conn->close();
        return $result;
    }

    function getUserDetailsByEmail($email){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT user_id, username, email, password, profile_pic, role, created_at FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $conn->close();
        return $result;
    }

    function registerUser($username, $email, $password, $role = "user"){
        $conn = connectToDB();
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        $stmt->execute();
        $result = $stmt->affected_rows;
        $stmt->close();
        $conn->close();
        return $result;
    }

    //Movie Details Queries
    function getAllMovies(){
        global $conn;
        $stmt = $conn->prepare("SELECT movie_id, movie_title, movie_description, genre, ratings FROM movies");
        $stmt->execute();
        return $stmt->get_result();
    }

    //Review Details Queries
    function getAllReviews(){
        global $conn;
        $stmt = $conn->prepare("SELECT review_id, user_id, movie_id, review, rating, created_at FROM reviews");
        $stmt->execute();
        return $stmt->get_result();
    }

    function getReviewsByUserId($user_id){
        global $conn;
        $stmt = $conn->prepare("SELECT review_id, movie_id, review, rating, created_at FROM reviews WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getReviewsByMovieId($movie_id){
        global $conn;
        $stmt = $conn->prepare("SELECT review_id, user_id, review, rating, created_at FROM reviews WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getReviewById($review_id){
        global $conn;
        $stmt = $conn->prepare("SELECT user_id, movie_id, review, rating, created_at FROM reviews WHERE review_id = ?");
        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    //Watchlist Queries
    function getAllMoviesFromWatchList(){
        global $conn;
        $stmt = $conn->prepare("SELECT movie_id, user_id, created_at FROM watch_list");
        $stmt->execute();
        return $stmt->get_result();
    }

    function getMovieFromWatchListByUserId($user_id){
        global $conn;
        $stmt = $conn->prepare("SELECT movie_id, created_at FROM watch_list WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }
?>