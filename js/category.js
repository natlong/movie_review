document.addEventListener("DOMContentLoaded", () => {
  const movieGrid = document.getElementById("movie-grid");
  const genreButtons = document.querySelectorAll(".genre-btn");

  if (genreButtons.length > 0) {
    genreButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        // Toggle active class on click
        btn.classList.toggle("active");

        const selectedGenreIds = Array.from(document.querySelectorAll(".genre-btn.active"))
                                      .map(b => b.getAttribute("data-genre-id"));
        fetchGenreMovies(selectedGenreIds);
      });
    });

    // Optional: preload first genre
    const defaultGenre = genreButtons[0].getAttribute("data-genre-id");
    genreButtons[0].classList.add("active");
    fetchGenreMovies([defaultGenre]);
  }

  function fetchGenreMovies(genreIds) {
    const apiKey = "0898e5d05464d2b33011428dac1eee0f";

    // If no genres are selected, don't fetch anything
    if (genreIds.length === 0) {
      movieGrid.innerHTML = `<p class="genre-warning">Please select at least one genre.</p>`;
      return;
    }

    const url = `https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&with_genres=${genreIds.join(',')}&sort_by=popularity.desc`;

    fetch(url)
      .then((res) => res.json())
      .then((data) => {
        movieGrid.innerHTML = "";

        data.results.forEach((movie) => {
          const card = document.createElement("div");
          card.classList.add("movie-card");
          card.setAttribute("data-rating", movie.vote_average);
          card.setAttribute("data-year", movie.release_date?.substring(0, 4) || "");

          card.innerHTML = `
            <img src="https://image.tmdb.org/t/p/w500${movie.poster_path}" alt="${movie.title}">
            <div class="movie-details">
              <h3>${movie.title}</h3>
              <p class="meta">${movie.release_date} &nbsp; ⭐ ${movie.vote_average.toFixed(1)}</p>
              <a href="movie_info.php?id=${movie.id}" class="btn">View Details</a>
            </div>
          `;

          movieGrid.appendChild(card);
        });

        if (data.results.length === 0) {
          movieGrid.innerHTML = "<p>No movies found for the selected genres.</p>";
        }
      })
      .catch((err) => {
        console.error("Failed to fetch movies by genre:", err);
        movieGrid.innerHTML = "<p>Something went wrong while loading movies.</p>";
      });
  }
});
