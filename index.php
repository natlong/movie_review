<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'inc/head.inc.php'; ?>
    </head>
    <body>

    <!-- Navigation Bar -->
    <nav>
        <img src="images/MovieVerse_Logo.png" alt="MovieVerse Logo" class="nav-logo">
        <div class="nav-controls">
            <!-- Sorting Dropdown -->
            <div class="nav-sort">
                <select id="sort-select" onchange="sortMovies()">
                    <option value="default">Sort By</option>
                    <option value="rating-desc">Rating High to Low</option>
                    <option value="rating-asc">Rating Low to High</option>
                    <option value="year-desc">Year Newest First</option>
                    <option value="year-asc">Year Oldest First</option>
                </select>
            </div>

            <!-- Search & Category Dropdown (Pill Container) -->
            <div class="nav-search">
                <button id="dropdown-button">All ⬇</button>
                <div id="dropdown-menu" class="hidden">
                    <ul>
                        <li class="category-option" data-category="All">All</li>
                        <li class="category-option" data-category="Action">Action</li>
                        <li class="category-option" data-category="Drama">Drama</li>
                        <li class="category-option" data-category="Sci-Fi">Sci-Fi</li>
                    </ul>
                </div>
                <input type="search" id="search-input" placeholder="Search movies..." oninput="searchMovies()" required/>
                <button class="search-btn" onclick="searchMovies()">🔍</button>
            </div>

            <!-- Profile Icon for Sign In / Sign Up -->
            <div id="profile-container">
                <span class="profile-icon">&#128100;</span>
                <div id="profile-dropdown" class="profile-dropdown hidden">
                    <ul>
                        <li><a href="login.html">Login</a></li>
                        <li><a href="signup.html">Sign Up</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
        <main>
            <!-- Top 10 Movies Section -->
            <header class="header-title">🔥 Top 10 on MovieVerse this week</header>
            <div class="relative px-6 py-4 movies-container">
                <button class="scroll-btn left-btn" onclick="scrollLeft()">◀</button>
                <div id="movies-scroll" class="scroll-container space-x-4"></div>
                <button class="scroll-btn right-btn" onclick="scrollRight()">▶</button>
            </div>

            <!-- Recommended Movies Section -->
            <header class="header-title">🎬 Recommended for You</header>
            <div class="relative px-6 py-4 recommended-container">
                <button class="scroll-btn left-btn" onclick="scrollRecommendedLeft()">◀</button>
                <div id="recommended-scroll" class="scroll-container space-x-4"></div>
                <button class="scroll-btn right-btn" onclick="scrollRecommendedRight()">▶</button>
            </div>

    
        </main>
        <?php include 'inc/footer.inc.php'; ?>
    </body>
</html>
