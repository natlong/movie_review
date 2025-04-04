<?php
$apiKey = "0898e5d05464d2b33011428dac1eee0f";
$region = "SG";
$url = "https://api.themoviedb.org/3/movie/now_playing?api_key=$apiKey&region=$region";

$response = file_get_contents($url);
$data = json_decode($response, true);

if (!empty($data['results'])) {
    echo "<h2>🎬 Now Showing in Singapore</h2>";
    echo "<div class='scroll-container'>";
    foreach ($data['results'] as $movie) {
        $title = htmlspecialchars($movie['title']);
        $poster = $movie['poster_path'] 
            ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] 
            : "images/image_not_found.jpg";
        $releaseDate = $movie['release_date'];
        $rating = number_format($movie['vote_average'], 1);

        echo "
        <div class='movie-card'>
            <img src='$poster' alt='$title'>
            <h3>$title</h3>
            <p class='movie-info'>$releaseDate • ⭐ $rating</p>
            <a href='movie_info.php?id={$movie['id']}' class='btn'>View Details</a>
        </div>";
    }
    echo "</div>";
} else {
    echo "<p>No movies currently showing.</p>";
}
?>
