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
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT movie_id, movie_title, movie_description, genre, ratings FROM movie");
        $stmt->execute();
        return $stmt->get_result();
    }
    function getAllMoviesByMovieId($movie_id){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT movie_id, movie_title, movie_description, genre, ratings FROM movie WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Returns movie data if found
        } else {
            return null;  // Return null if no movie found
        }

        
    }

    //Review Details Queries
    function getAllReviews(){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT review_id, user_id, movie_id, review, rating, created_at FROM reviews");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }

    function getReviewsByUserId($user_id){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT review_id, movie_id, review, rating, created_at FROM reviews WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    
        $stmt->close();
        $conn->close();
        return $reviews; // ✅ Return array of reviews
    }
    

    function getReviewsByMovieId($movie_id) {
        $conn = connectToDB();
        $stmt = $conn->prepare(
            "SELECT r.review_id, r.user_id, r.review, r.rating, r.created_at, u.username, u.profile_pic
             FROM reviews r
             JOIN users u ON r.user_id = u.user_id
             WHERE r.movie_id = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    
        $stmt->close();
        $conn->close();
        return $reviews;
    }
    

    function getReviewById($review_id){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT user_id, movie_id, review, rating, created_at FROM reviews WHERE review_id = ?");
        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }

    //Watchlist Queries
    function getAllMoviesFromWatchList(){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT movie_id, user_id, created_at FROM watchlist");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }

    function getMovieFromWatchListByUserId($user_id){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT movie_id, created_at FROM watchlist WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $movies = [];
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
    
        $stmt->close();
        $conn->close();
        return $movies;  // ✅ Ensure you're returning an array
    }
    
    
    //reviews 
    function getUserById($user_id) {
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $result;
    }
    

    //Liked Movies Queries
    function getAllMoviesFromLikedMovies(){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT movie_id, user_id, created_at FROM movie_likes");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }

    function getAllMoviesFromLikedMoviesByUserId($user_id){
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT movie_id, created_at FROM movie_likes WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
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
    
    function fetchMovieDetails($movieId) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';  // your API key
    
        // Fetch movie details from TMDb API
        $details = json_decode(file_get_contents("https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&append_to_response=videos,credits,recommendations"), true);
    
        // If no movie found, return false
        if (!$details || isset($details['status_code'])) {
            return false; // or handle the error gracefully
        }
    
        // Prepare the movie data
        $movieData = [
            'title' => htmlspecialchars($details['title']),
            'poster' => "https://image.tmdb.org/t/p/w500" . $details['poster_path'],
            'overview' => $details['overview'],
            'rating' => number_format($details['vote_average'], 2),
            'release' => $details['release_date'],
            'genres' => implode(', ', array_column($details['genres'], 'name')),
            'videoKey' => $details['videos']['results'][0]['key'] ?? null,
            'cast' => array_slice($details['credits']['cast'], 0, 5),
            'recommendations' => array_slice($details['recommendations']['results'], 0, 5)
        ];
    
        // Check if the movie exists in your database
        // $movieExists = getAllMoviesByMovieId($movieId);  // Your function to check if the movie exists in DB
    
        // Return both movie data and existence check result
        // return ['movieData' => $movieData, 'movieExists' => $movieExists];
        return ['movieData' => $movieData];
    }
    //top movie
    function fetchTopRatedMovies($limit = 50) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';
        $topMovies = [];
    
        $pages = ceil($limit / 20);
        for ($page = 1; $page <= $pages; $page++) {
            $url = "https://api.themoviedb.org/3/movie/top_rated?api_key=$apiKey&page=$page";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
    
            if (!empty($data['results'])) {
                $topMovies = array_merge($topMovies, $data['results']);
            }
        }
    
        return array_slice($topMovies, 0, $limit);
    }
    //search 
    function searchMovies($query) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';
        $url = 'https://api.themoviedb.org/3/search/movie?api_key=' . $apiKey . '&query=' . urlencode($query);
    
        $response = @file_get_contents($url);
        if ($response === FALSE) {
            return []; // Fail silently
        }
    
        $data = json_decode($response, true);
        return $data['results'] ?? [];
    }
    
    
    
?>











