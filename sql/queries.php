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
        if($stmt->execute()){
            $user_id = $conn->insert_id;
        }else{
            $user_id = false;
        }
        $stmt->close();
        $conn->close();
        return $user_id;
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

    //Liked Movies Queries
    function getAllMoviesFromLikedMovies(){
        global $conn;
        $stmt = $conn->prepare("SELECT movie_id, user_id, created_at FROM movie_likes");
        $stmt->execute();
        return $stmt->get_result();
    }

    function getAllMoviesFromLikedMoviesByUserId($user_id){
        global $conn;
        $stmt = $conn->prepare("SELECT movie_id, created_at FROM movie_likes WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }


    //API Queries - API Key 0898e5d05464d2b33011428dac1eee0f

    function getTrendingMovies($limit = 10) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';
        $url = "https://api.themoviedb.org/3/trending/movie/week?api_key=$apiKey";
    
        $response = file_get_contents($url);
        $data = json_decode($response, true);
    
        return array_slice($data['results'], 0, $limit);
    }

    function getRecommendedMovies($movie_id, $limit = 10) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';
        $url = "https://api.themoviedb.org/3/movie/$movie_id/recommendations?api_key=$apiKey";
    
        $response = file_get_contents($url);
        $data = json_decode($response, true);
    
        return array_slice($data['results'], 0, $limit);
    }

    function searchMovieId($movie_title) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';
        $query = urlencode($movie_title);
        $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=$query";
    
        $response = file_get_contents($url);
        $data = json_decode($response, true);
    
        // Get the first matching result
        return $data['results'][0]['id'] ?? null;
    }
    
    
    
?>











