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

    function getTopGenresFromWatchlist($user_id, $limit = 3) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';
        $genreCount = [];
    
        // 1. Fetch movie_ids from user's watchlist (assuming you have a DB function)
        $watchlist = getMovieFromWatchListByUserId($user_id); // array of TMDB movie IDs
    
        foreach ($watchlist as $entry) {
            $movie_id = $entry['movie_id'];
            $url = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$apiKey";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
    
            if (!empty($data['genres'])) {
                foreach ($data['genres'] as $genre) {
                    $genreId = $genre['id'];
                    $genreCount[$genreId] = ($genreCount[$genreId] ?? 0) + 1;
                }
            }
        }
    
        // Sort genres by frequency
        arsort($genreCount);
    
        // Return the top N genre IDs
        return array_slice(array_keys($genreCount), 0, $limit);
    }

    function getTopMoviesByGenresExcluding($genre_ids = [], $exclude_ids = [], $limit = 10) {
        $apiKey = '0898e5d05464d2b33011428dac1eee0f';
        $uniqueMovies = [];
    
        foreach ($genre_ids as $genre_id) {
            $url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&with_genres=$genre_id&sort_by=popularity.desc";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
    
            if (!empty($data['results'])) {
                foreach ($data['results'] as $movie) {
                    if (in_array($movie['id'], $exclude_ids)) {
                        continue;
                    }
                    $uniqueMovies[$movie['id']] = $movie;
                }
            }
    
            if (count($uniqueMovies) >= $limit) {
                break;
            }
        }
    
        return array_slice(array_values($uniqueMovies), 0, $limit);
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
        $stmt = $conn->prepare("SELECT movie_id FROM watchlist WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $movies = [];
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
    
        $stmt->close();
        $conn->close();
        return $movies;  // 
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
        $results = [];
    
        // TMDb API search
        $apiKey = 'YOUR_API_KEY';
        $url = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query=" . urlencode($query);
        $apiResponse = file_get_contents($url);
        $data = json_decode($apiResponse, true);
        if ($data && isset($data['results'])) {
            $results = array_slice($data['results'], 0, 10); // Limit API results
        }
    
        // Local DB search
        $conn = connectToDB();
        if ($conn) {
            $stmt = $conn->prepare("SELECT * FROM movie WHERE movie_title LIKE CONCAT('%', ?, '%')");
            $stmt->bind_param("s", $query);
            $stmt->execute();
            $dbResult = $stmt->get_result();
    
            while ($row = $dbResult->fetch_assoc()) {
                $results[] = [
                    'id' => $row['movie_id'],
                    'title' => $row['movie_title'],
                    'release_date' => 'N/A',
                    'vote_average' => 0,
                    'poster_path' => $row['img_link'] // your local img
                ];
            }
    
            $stmt->close();
            $conn->close();
        }
    
        return $results;
    }
    
    // Profile picture functions
    
    /**
     * Update user's profile picture in the users table
     * 
     * @param int $userId The user ID
     * @param string $path Path to the profile picture
     * @return bool True if successful, false otherwise
     */
    function updateUserProfilePic($userId, $path) {
        $conn = connectToDB();
        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE user_id = ?");
        $stmt->bind_param("si", $path, $userId);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    /**
     * Get user's profile information including profile picture
     * 
     * @param int $userId The user ID
     * @return array|null User data or null if not found
     */
    function getUserProfile($userId) {
        $conn = connectToDB();
        $stmt = $conn->prepare("SELECT username, email, profile_pic, role, created_at FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        } else {
            $userData = null;
        }
        
        $stmt->close();
        $conn->close();
        return $userData;
    }

    /**
     * Update user's profile picture in the users table
     * This function is designed to be used with the profile picture upload feature
     * 
     * @param int $userId The user ID
     * @param string $picturePath Path to the profile picture
     * @return bool True if successful, false otherwise
     */
    function updateProfilePicture($userId, $picturePath) {
        return updateUserProfilePic($userId, $picturePath);
    }

    function updateUserPassword($user_id, $new_password) {
        $conn = connectToDB();
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }
    
?>