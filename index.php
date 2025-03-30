<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'inc/head.inc.php'; ?>
    </head>
    <body>
        <?php include 'inc/nav.inc.php'; 
            session_start();
            require_once 'sql/queries.php';
            $trendingMovies = getTrendingMovies(10); // Fetch top 10 trending movies
            // only if they are logged in $recommendedMovies = getRecommendedMovies(10); // Fetch top 10 recommended movies
            
        ?>
        <div class="page-wrapper">
            <header class="header-title">
                <h1>Welcome to MovieVerse</h1>
                <p>Your one-stop destination for all things movies!</p>
            </header>

            <h3 class="section-header">🔥 Top 10 Movies this week</h3>
            <div class="scroll-container" id="movies-scroll">
                <?php foreach ($trendingMovies as $movie): ?>
                    <a href="movie_info.php?id=<?= $movie['id'] ?>" class="movie-link" style="text-decoration: none;">
                        <div class="movie-card">
                        <img src="<?= $movie['poster_path'] 
                            ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] 
                            : 'images/image_not_found.jpg' ?>" 
                            alt="<?= $movie['title'] ?>" 
                            class="movie-img">
                            <!-- <h3><?= $movie['title'] ?></h3> -->
                            <h3 title="<?= htmlspecialchars($movie['title']) ?>">
                                <?= htmlspecialchars($movie['title']) ?>
                            </h3>
                            <p class="movie-info"><?= $movie['release_date'] ?> • ⭐ <?= number_format($movie['vote_average'], 2) ?></p>
                            <button class="btn">+ Watchlist</button>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- <main>
                Top 10 Movies Section 
                <header class="header-title">🔥 Top 10 on MovieVerse this week</header>
                <div class="relative px-6 py-4 movies-container">
                    <button class="scroll-btn left-btn" onclick="scrollLeft()">◀</button>
                    <div id="movies-scroll" class="scroll-container space-x-4"></div>
                    <button class="scroll-btn right-btn" onclick="scrollRight()">▶</button>
                </div>

                Recommended Movies Section 
                <header class="header-title">🎬 Recommended for You</header>
                <div class="relative px-6 py-4 recommended-container">
                    <button class="scroll-btn left-btn" onclick="scrollRecommendedLeft()">◀</button>
                    <div id="recommended-scroll" class="scroll-container space-x-4"></div>
                    <button class="scroll-btn right-btn" onclick="scrollRecommendedRight()">▶</button>
                </div>

        
            </main> -->
            <?php include 'inc/footer.inc.php'; ?>
        </div>
    </body>
</html>
