<nav>
  <a href="index.php">
      <img src="images/MovieVerse_Logo.png" alt="MovieVerse Logo" class="nav-logo">
  </a>
  <div class="nav-controls">
      <div class="nav-sort">
          <select id="sort-select" onchange="sortMovies()">
              <option value="default">Sort By</option>
              <option value="rating-desc">Rating High to Low</option>
              <option value="rating-asc">Rating Low to High</option>
              <option value="year-desc">Year Newest First</option>
              <option value="year-asc">Year Oldest First</option>
          </select>
      </div>
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
      <div id="profile-container">
          <span class="profile-icon">&#128100;</span>
          <div id="profile-dropdown" class="profile-dropdown hidden">
              <ul>
                  <li><a href="login.php">Login</a></li>
                  <li><a href="signup.php">Sign Up</a></li>
              </ul>
          </div>
      </div>
  </div>
</nav>