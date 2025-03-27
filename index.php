<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'inc/head.inc.php'; ?>
    </head>
    <body>
    <?php include 'inc/nav.inc.php'; ?>
    
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
