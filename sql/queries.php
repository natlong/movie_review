<?php
    require_once(__DIR__ . "/config.php");

    //User Details Queries
    function getAllUsers(){
        global $conn;
        $stmt = $conn->prepare("SELECT user_id, username, email, password, profile_pic, role, created_at FROM users");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
    }

    function getUserDetailsByEmail($email){
        global $conn;
        $stmt = $conn->prepare("SELECT user_id, username, email, password, profile_pic, role, created_at FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result();
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