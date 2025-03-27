const scrollContainer = document.getElementById("movies-scroll");
const recommendedContainer = document.getElementById("recommended-scroll");
let selectedCategory = "All"; // Default category

// Sample movie data with extra properties
const movies = [
  { 
    title: "My Fault: London", 
    rating: 7.6, 
    image: "images/fault.jpg", 
    category: "Drama",
    year: 2020,
    runtime: "1h 40m",
    genres: ["Drama", "Romance"]
  },
  { 
    title: "The White Lotus", 
    rating: 8.0, 
    image: "images/white.jpg", 
    category: "Drama",
    year: 2021,
    runtime: "1h 30m",
    genres: ["Drama", "Mystery"]
  },
  { 
    title: "Daredevil: Born Again", 
    rating: 8.8, 
    image: "images/daredevil.jpg", 
    category: "Action",
    year: 2022,
    runtime: "2h 15m",
    genres: ["Action", "Superhero"]
  },
  { 
    title: "Severance", 
    rating: 8.7, 
    image: "images/severance.jpg", 
    category: "Sci-Fi",
    year: 2023,
    runtime: "1h 45m",
    genres: ["Sci-Fi", "Thriller"]
  },
  { 
    title: "The Brutalist", 
    rating: 7.5, 
    image: "images/brutalist.jpg", 
    category: "Drama",
    year: 2019,
    runtime: "1h 50m",
    genres: ["Drama"]
  }
];

// Recommended Movies with extra properties
const recommendedMovies = [
  { 
    title: "Blade Runner 2049", 
    rating: 8.0, 
    image: "images/bladerunner.jpg", 
    category: "Sci-Fi",
    year: 2017,
    runtime: "2h 44m",
    genres: ["Sci-Fi", "Thriller"]
  },
  { 
    title: "Interstellar", 
    rating: 8.6, 
    image: "images/interstellar.jpg", 
    category: "Sci-Fi",
    year: 2014,
    runtime: "2h 49m",
    genres: ["Sci-Fi", "Adventure"]
  },
  { 
    title: "Inception", 
    rating: 8.8, 
    image: "images/inception.jpg", 
    category: "Action",
    year: 2010,
    runtime: "2h 28m",
    genres: ["Action", "Sci-Fi"]
  },
  { 
    title: "Dune", 
    rating: 8.1, 
    image: "images/dune.jpg", 
    category: "Sci-Fi",
    year: 2021,
    runtime: "2h 35m",
    genres: ["Sci-Fi", "Adventure"]
  }
];

// Helper function to render movies in a container
function renderMovies(moviesArray, container) {
  container.innerHTML = "";
  moviesArray.forEach(movie => {
    const movieCard = document.createElement("div");
    movieCard.classList.add("movie-card");
    movieCard.innerHTML = `
      <img src="${movie.image}" alt="${movie.title}" class="movie-img">
      <h3>${movie.title}</h3>
      <p class="movie-info">${movie.year} • ${movie.runtime} • ${movie.genres.join(", ")}</p>
      <p class="movie-rating">⭐ ${movie.rating}</p>
      <button class="btn">+ Watchlist</button>
    `;
    container.appendChild(movieCard);
  });
}

// Sort an array based on the current sort selection
function sortArray(arr) {
  const sortValue = document.getElementById("sort-select").value;
  const sortedArr = arr.slice();
  if (sortValue === "rating-desc") {
    sortedArr.sort((a, b) => b.rating - a.rating);
  } else if (sortValue === "rating-asc") {
    sortedArr.sort((a, b) => a.rating - b.rating);
  } else if (sortValue === "year-desc") {
    sortedArr.sort((a, b) => b.year - a.year);
  } else if (sortValue === "year-asc") {
    sortedArr.sort((a, b) => a.year - b.year);
  }
  return sortedArr;
}

// Load Top Movies (with filtering and sorting)
function loadMovies(category = "All") {
  const filteredMovies = (category === "All")
    ? movies
    : movies.filter(movie => movie.category === category);
  const sortedMovies = sortArray(filteredMovies);
  renderMovies(sortedMovies, scrollContainer);
}

// Load Recommended Movies (with filtering and sorting)
function loadRecommendedMovies(category = "All") {
  const filteredMovies = (category === "All")
    ? recommendedMovies
    : recommendedMovies.filter(movie => movie.category === category);
  const sortedMovies = sortArray(filteredMovies);
  renderMovies(sortedMovies, recommendedContainer);
}

// Scroll Functions
function scrollLeft() {
  scrollContainer.scrollBy({ left: -300, behavior: "smooth" });
}
function scrollRight() {
  scrollContainer.scrollBy({ left: 300, behavior: "smooth" });
}
function scrollRecommendedLeft() {
  recommendedContainer.scrollBy({ left: -300, behavior: "smooth" });
}
function scrollRecommendedRight() {
  recommendedContainer.scrollBy({ left: 300, behavior: "smooth" });
}

// Search Movies
function searchMovies() {
  const searchQuery = document.getElementById("search-input").value.toLowerCase().trim();

  const filteredMovies = movies.filter(movie => {
    const matchesSearch = movie.title.toLowerCase().includes(searchQuery);
    const matchesCategory = (selectedCategory === "All" || movie.category === selectedCategory);
    return matchesSearch && matchesCategory;
  });

  const filteredRecommended = recommendedMovies.filter(movie => {
    const matchesSearch = movie.title.toLowerCase().includes(searchQuery);
    const matchesCategory = (selectedCategory === "All" || movie.category === selectedCategory);
    return matchesSearch && matchesCategory;
  });

  renderMovies(sortArray(filteredMovies), scrollContainer);
  renderMovies(sortArray(filteredRecommended), recommendedContainer);
}

// Function to re-sort movies on sort change
function sortMovies() {
  if (document.getElementById("search-input").value.trim() !== "") {
    searchMovies();
  } else {
    loadMovies(selectedCategory);
    loadRecommendedMovies(selectedCategory);
  }
}

// Toggle dropdown menu
document.getElementById("dropdown-button").addEventListener("click", () => {
  const dropdownMenu = document.getElementById("dropdown-menu");
  dropdownMenu.classList.toggle("hidden");
});

// Handle category selection
document.querySelectorAll(".category-option").forEach(option => {
  option.addEventListener("click", function() {
    selectedCategory = this.dataset.category;
    document.getElementById("dropdown-button").textContent = `${selectedCategory} ⬇`;
    // Hide the dropdown after selection
    document.getElementById("dropdown-menu").classList.add("hidden");
    // Clear search input when changing category
    document.getElementById("search-input").value = "";
    // Reload movies with current sort settings
    loadMovies(selectedCategory);
    loadRecommendedMovies(selectedCategory);
  });
});

// Load movies on page load
document.addEventListener("DOMContentLoaded", () => {
  loadMovies();
  loadRecommendedMovies();
});
