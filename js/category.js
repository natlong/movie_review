document.addEventListener("DOMContentLoaded", () => {
  const genreButtons = document.querySelectorAll(".genre-btn");
  const movieGrid = document.getElementById("movie-grid");

  // Function to fetch movies for a given genre
  function fetchGenreMovies(genreId) {
    const apiKey = "0898e5d05464d2b33011428dac1eee0f";
    const url = `https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&with_genres=${genreId}`;

    fetch(url)
      .then((res) => res.json())
      .then((data) => {
        movieGrid.innerHTML = "";
        data.results.forEach((movie) => {
          const card = document.createElement("a");
          card.classList.add("movie-card");
          card.href = `movie_info.php?id=${movie.id}`;
          card.setAttribute("data-rating", movie.vote_average);
          card.setAttribute("data-year", movie.release_date?.substring(0, 4) || "");

          card.innerHTML = `
            <img src="https://image.tmdb.org/t/p/w500${movie.poster_path}" alt="${movie.title}">
            <h3>${movie.title}</h3>
            <p class="movie-info">${movie.release_date} • ⭐ ${movie.vote_average.toFixed(1)}</p>
          `;
          movieGrid.appendChild(card);
        });
      })
      .catch((err) => {
        console.error("Failed to fetch movies by genre:", err);
        movieGrid.innerHTML = "<p>Something went wrong while loading movies.</p>";
      });
  }

  // Add click event listeners to genre buttons
  if (genreButtons) {
    genreButtons.forEach((button) => {
      button.addEventListener("click", () => {
        // Remove 'active' class from all buttons
        genreButtons.forEach((btn) => btn.classList.remove("active"));
        // Add 'active' class to the clicked button
        button.classList.add("active");

        // Fetch movies for the selected genre
        const genreId = button.getAttribute("data-genre-id");
        fetchGenreMovies(genreId);
      });
    });

    // Load movies for the default (active) genre on page load
    const defaultGenreId = document.querySelector(".genre-btn.active").getAttribute("data-genre-id");
    fetchGenreMovies(defaultGenreId);
  }
});