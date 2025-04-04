<<<<<<< Updated upstream
<?php 
// Ensure session is started 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
?>

<script src="/js/script.js?v=<?= time() ?>" defer></script>

<nav>
  <a href="<?= (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'admin_index.php' : 'index.php' ?>">
    <img src="images/MovieVerse_Logo.png" alt="MovieVerse Logo" class="nav-logo">
  </a>
  
  <a href="top_movie.php" class="nav-link">🏆 Top 250</a>
  <a href="category.php" class="nav-link">🏆 Category</a>
  
  <!-- Admin-Only Insert Movie Link -->
  <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="insert.php" class="nav-link"> ➕Insert Movie</a>
    <a href="admin_support.php" class="nav-link"> View Support Messages</a>
  <?php endif; ?>
  
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
  
  <div class="nav-controls">
    <!-- Sorting -->
    <div class="nav-sort">
      <label for="sort-select">Options:</label>
      <select id="sort-select" onchange="sortMovies()">
        <option value="default">Sort By</option>
        <option value="rating-desc">Rating High to Low</option>
        <option value="rating-asc">Rating Low to High</option>
        <option value="year-desc">Year Newest First</option>
        <option value="year-asc">Year Oldest First</option>
      </select>
    </div>
    
    <!-- Popup Modal HTML -->
    <div id="watchlist-modal" class="watchlist-modal hidden">
      <div class="watchlist-modal-content">
        <span class="watchlist-modal-close" onclick="closeWatchlistModal()">&times;</span>
        <p id="watchlist-modal-message"></p>
      </div>
    </div>
    
    <!-- Search with category dropdown -->
    <form id="search-form" action="search.php" method="get" class="nav-search">
      <input
        type="search"
        name="query"
        id="search-input"
        placeholder="Search movies..."
        value="<?= htmlspecialchars($_GET['query'] ?? '') ?>"
        required
      />
      <button type="submit" class="search-btn">🔍</button>
    </form>
    
    <!-- Auth/Profile Section -->
    <div id="auth-container">
      <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="auth-links">
          <a href="login.php" class="auth-link">Login</a>
          <a href="signup.php" class="auth-link">Register</a>
        </div>
      <?php else: ?>
        <div id="profile-toggle" class="profile-toggle">
          <?php if (isset($_SESSION['profile_pic']) && !empty($_SESSION['profile_pic'])): ?>
            <img src="<?= htmlspecialchars($_SESSION['profile_pic']) ?>" alt="Profile" class="profile-pic">
          <?php else: ?>
            <span class="profile-icon">&#128100;</span>
          <?php endif; ?>
          <span class="profile-name">
            <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
          </span>
        </div>
        <div id="profile-dropdown" class="profile-dropdown hidden">
          <ul>
            <li><a href="profile.php">My Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>
=======
<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
<script src="/js/script.js?v=<?= time() ?>" defer></script>
<?php include 'inc/head.inc.php'; ?>

<div class="fixed-top">
  <nav>
    <!-- Left Section: Logo and Primary Links -->
    <div class="nav-left">
      <a href="index.php">
        <img src="images/MovieVerse_Logo.png" alt="MovieVerse Logo" class="nav-logo">
      </a>
      <div class="nav-links">
        <a href="top_movie.php" class="nav-link">🏆 Top 250</a>
        <a href="category.php" class="nav-link">🎥 Genre</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="insert.php" class="nav-link">➕ Insert Movie</a>
          <a href="admin_support.php" class="nav-link">📩 Support Messages</a>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Right Section: Controls (Sorting, Search, and Auth/Profile) -->
    <div class="nav-controls">
      <!-- Sorting -->
      <div class="nav-sort">
        <select id="sort-select" onchange="sortMovies()">
          <option value="default">Sort By</option>
          <option value="rating-desc">Rating High to Low</option>
          <option value="rating-asc">Rating Low to High</option>
          <option value="year-desc">Year Newest First</option>
          <option value="year-asc">Year Oldest First</option>
        </select>
      </div>
  
      <!-- Watchlist Modal -->
      <div id="watchlist-modal" class="watchlist-modal hidden">
        <div class="watchlist-modal-content">
          <span class="watchlist-modal-close" onclick="closeWatchlistModal()">&times;</span>
          <p id="watchlist-modal-message"></p>
        </div>
      </div>
  
      <!-- Search Bar -->
      <form id="search-form" action="search.php" method="get" class="nav-search">
        <input
          type="search"
          name="query"
          id="search-input"
          placeholder="Search movies..."
          value="<?= htmlspecialchars($_GET['query'] ?? '') ?>"
          required
        />
        <button type="submit" class="search-btn">🔍</button>
      </form>
  
      <!-- Auth/Profile -->
      <div id="auth-container">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <div class="auth-links">
            <a href="login.php" class="auth-link">Login</a>
            <a href="signup.php" class="auth-link">Register</a>
          </div>
        <?php else: ?>
          <!-- Profile Pic & Name -->
          <div id="profile-toggle" class="nav-profile-pic" tabindex="0">
            <?php if (!empty($_SESSION['profile_pic'])): ?>
              <img src="<?= htmlspecialchars($_SESSION['profile_pic']) ?>" alt="Profile" class="profile-pic">
            <?php else: ?>
              <span class="profile-icon">&#128100;</span>
            <?php endif; ?>
            <span class="profile-name"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
          </div>
  
          <!-- Dropdown -->
          <div id="profile-dropdown" class="profile-dropdown hidden">
            <ul>
              <li><a href="profile.php">👤 My Profile</a></li>
              <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</div>

>>>>>>> Stashed changes
