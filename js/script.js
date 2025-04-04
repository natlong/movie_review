// Navigate to review page
function navigateToReviewPage(movieId) {
  window.location.href = 'reviews.php?movie=' + movieId;
}

let currentFeatured = 0;
let spotlightInterval;
let spotlightPaused = false;

// Show the current featured movie
function showFeatured(index) {
  const cards = document.querySelectorAll(".featured-card");
  if (!cards.length) return;

  cards.forEach((card, i) => {
    card.classList.toggle("active", i === index);
    card.style.opacity = i === index ? "1" : "0";
    card.style.transition = "opacity 0.8s ease-in-out";
  });

  currentFeatured = index;
}

// Go to the next featured movie
function nextFeatured() {
  const cards = document.querySelectorAll(".featured-card");
  if (!cards.length) return;

  const nextIndex = (currentFeatured + 1) % cards.length;
  showFeatured(nextIndex);
}

// Go to the previous featured movie
function prevFeatured() {
  const cards = document.querySelectorAll(".featured-card");
  if (!cards.length) return;

  const prevIndex = (currentFeatured - 1 + cards.length) % cards.length;
  showFeatured(prevIndex);
}

// Start the auto-rotation
function startSpotlightInterval() {
  spotlightInterval = setInterval(() => {
    if (!spotlightPaused) {
      nextFeatured();
    }
  }, 5000);
}

// Stop the auto-rotation
function stopSpotlightInterval() {
  clearInterval(spotlightInterval);
}

// Attach event listeners to popcorn icons for rating (only for index.php/index.html)
function attachPopcornEventListeners() {
  const popcornIcons = document.querySelectorAll('.movies-wrapper .popcorn-icon'); // Only target popcorn icons in the "Top 10 Movies" section
  const ratingOverlay = document.getElementById('rating-overlay');
  const ratingPopup = document.getElementById('rating-popup');
  const popupMovieTitle = document.getElementById('popup-movie-title');
  const popcornRating = document.getElementById('popcorn-rating');
  const popcornImages = popcornRating?.querySelectorAll('img') || [];
  const submitRatingBtn = document.getElementById('submit-rating');

  let selectedRating = 0;
  let currentMovieId = null;

  popcornIcons.forEach(icon => {
    icon.addEventListener('click', (e) => {
      e.preventDefault(); // Prevent the surrounding <a> tag from navigating
      e.stopPropagation(); // Stop the event from bubbling up to the <a> tag
      currentMovieId = icon.getAttribute('data-movie-id');
      const movieTitle = icon.getAttribute('data-movie-title');
      popupMovieTitle.textContent = movieTitle;

      // Reset previous selections
      selectedRating = 0;
      popcornImages.forEach(img => img.classList.remove('selected'));

      // Show the popup and overlay
      if (ratingOverlay && ratingPopup) {
        ratingOverlay.style.display = 'block';
        ratingPopup.style.display = 'block';
      }
    });
  });

  popcornImages.forEach(img => {
    img.addEventListener('click', () => {
      selectedRating = parseInt(img.getAttribute('data-value'));
      popcornImages.forEach(p => p.classList.remove('selected'));
      for (let i = 0; i < selectedRating; i++) {
        popcornImages[i].classList.add('selected');
      }
    });
  });

  if (submitRatingBtn) {
    submitRatingBtn.addEventListener('click', () => {
      if (selectedRating > 0) {
        console.log(`Movie ID: ${currentMovieId}, Rating: ${selectedRating} popcorns`);
        alert(`You rated ${popupMovieTitle.textContent} with ${selectedRating}/5 popcorns!`);

        // Here you can add code to save the rating to a database via an API call
        // Example: fetch('submit_rating.php', { method: 'POST', body: JSON.stringify({ movieId: currentMovieId, rating: selectedRating }) });

        // Close the popup
        if (ratingOverlay && ratingPopup) {
          ratingOverlay.style.display = 'none';
          ratingPopup.style.display = 'none';
        }
      } else {
        alert('Please select a rating before submitting.');
      }
    });
  }

  if (ratingOverlay) {
    ratingOverlay.addEventListener('click', () => {
      ratingOverlay.style.display = 'none';
      ratingPopup.style.display = 'none';
    });
  }
}

window.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Spotlight initialized");

  // Attach popcorn rating event listeners (only for index.php/index.html)
  attachPopcornEventListeners();

  showFeatured(currentFeatured);
  startSpotlightInterval();

  const spotlight = document.getElementById("featured-spotlight");
  if (spotlight) {
    spotlight.addEventListener("mouseenter", () => spotlightPaused = true);
    spotlight.addEventListener("mouseleave", () => spotlightPaused = false);
  }

  const nextBtn = document.getElementById("next-featured");
  const prevBtn = document.getElementById("prev-featured");

  if (nextBtn) nextBtn.addEventListener("click", nextFeatured);
  if (prevBtn) prevBtn.addEventListener("click", prevFeatured);
});

// Support.php
document.querySelectorAll('.faq-question').forEach(button => {
  button.addEventListener('click', () => {
    const answer = button.nextElementSibling;
    const plus = button.querySelector('.plus');
    const isOpen = answer.classList.contains('open');

    // Close all
    document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.plus').forEach(p => p.textContent = "+");

    if (!isOpen) {
      answer.classList.add('open');
      plus.textContent = "−";
    }
  });
});

// Nav bar 
document.addEventListener("DOMContentLoaded", () => {
  const profileToggle = document.getElementById("profile-toggle");
  const profileDropdown = document.getElementById("profile-dropdown");

  if (profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileDropdown.classList.toggle("hidden");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!profileDropdown.contains(e.target) && !profileToggle.contains(e.target)) {
        profileDropdown.classList.add("hidden");
      }
    });
  }

  // Category dropdown toggle
  const dropdownBtn = document.getElementById("dropdown-button");
  const dropdownMenu = document.getElementById("dropdown-menu");

  if (dropdownBtn && dropdownMenu) {
    dropdownBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdownMenu.classList.toggle("hidden");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
        dropdownMenu.classList.add("hidden");
      }
    });

    // Category filter handling (optional functionality)
    document.querySelectorAll(".category-option").forEach(option => {
      option.addEventListener("click", (e) => {
        const category = e.target.dataset.category;
        dropdownBtn.textContent = `${category} ⬇`;
        dropdownMenu.classList.add("hidden");

        // You can call a filter function here if needed
        // filterByCategory(category);
      });
    });
  }
});

function addToWatchlist(movieId) {
  fetch('add_to_watchlist.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `movie_id=${movieId}`
  })
  .then(res => res.json())
  .then(data => {
    showPopup(data.message, data.success ? 'success' : 'error');
  })
  .catch(err => {
    console.error(err);
    showPopup("Something went wrong.", "error");
  });
}

function showPopup(message, type) {
  const popup = document.createElement('div');
  popup.className = `watchlist-popup ${type}`;
  popup.textContent = message;
  document.body.appendChild(popup);

  setTimeout(() => popup.remove(), 3000);
}


// Review function
document.addEventListener("DOMContentLoaded", () => {
  const toggleReviewBtn = document.getElementById("toggle-review-form");
  const reviewForm = document.getElementById("review-form");
  const form = document.getElementById("submit-review-form");
  const messageDiv = document.getElementById("review-message");

  if (toggleReviewBtn && reviewForm) {
    toggleReviewBtn.addEventListener("click", () => {
      reviewForm.style.display = reviewForm.style.display === "none" ? "block" : "none";
    });
  }

  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      const res = await fetch("submit_review.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();
      messageDiv.textContent = result.message;
      messageDiv.style.color = result.success ? "lightgreen" : "red";

      if (result.success) {
        form.reset();
        reviewForm.style.display = "none";
        setTimeout(() => location.reload(), 1000); // reload to show the new review
      }
    });
  }
});

function sortMovies() {
  const sortValue = document.getElementById("sort-select").value;
  const movieCards = Array.from(document.querySelectorAll(".movie-card"));

  // Try all common containers
  const containers = [
    document.querySelector(".scroll-container"),
    document.querySelector(".movies-wrapper .movies-container"),
    document.querySelector(".movie-grid"),
    document.querySelector(".movie-list"),
    document.querySelector(".category-results")
  ];

  const container = containers.find(c => c !== null);
  if (!container || movieCards.length === 0) return;

  movieCards.sort((a, b) => {
    const ratingA = parseFloat(a.dataset.rating || 0);
    const ratingB = parseFloat(b.dataset.rating || 0);
    const yearA = parseInt(a.dataset.year || 0);
    const yearB = parseInt(a.dataset.year || 0);

    switch (sortValue) {
      case "rating-desc":
        return ratingB - ratingA;
      case "rating-asc":
        return ratingA - ratingB;
      case "year-desc":
        return yearB - yearA;
      case "year-asc":
        return yearA - yearB;
      default:
        return 0;
    }
  });

  // Clear and re-append sorted cards
  container.innerHTML = "";
  movieCards.forEach(card => container.appendChild(card));
}

document.querySelectorAll('.edit-review-btn').forEach(button => {
  button.addEventListener('click', () => {
    const reviewId = button.dataset.reviewId;
    const form = document.getElementById(`edit-form-${reviewId}`);
    const display = document.getElementById(`review-text-${reviewId}`);

    if (form && display) {
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
      display.style.display = display.style.display === 'none' ? 'block' : 'none';
    }
  });
});

document.querySelectorAll('.edit-review-form').forEach(form => {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const reviewId = form.dataset.reviewId;
    const formData = new FormData(form);
    const resultBox = form.querySelector('.edit-result');

    try {
      const response = await fetch('update_reviews.php', {
        method: 'POST',
        body: formData,
      });

      const result = await response.json();

      if (result.status === 'success') {
        const textDisplay = document.getElementById(`review-text-${reviewId}`);
        const ratingDisplay = document.getElementById(`review-rating-${reviewId}`);

        if (textDisplay) textDisplay.innerText = formData.get('review_text');
        if (ratingDisplay) ratingDisplay.innerText = `⭐ ${parseFloat(formData.get('rating')).toFixed(1)}`;

        form.style.display = 'none';
        if (textDisplay) textDisplay.style.display = 'block';
      }

      resultBox.textContent = result.message;
      resultBox.className = `edit-result ${result.status}`;
    } catch (error) {
      resultBox.textContent = 'An error occurred. Please try again.';
      resultBox.className = 'edit-result error';
    }
  });
});

document.querySelectorAll('.delete-review-btn').forEach(button => {
  button.addEventListener('click', async () => {
    const reviewId = button.dataset.reviewId;

    if (confirm("Are you sure you want to delete this review?")) {
      try {
        const res = await fetch('delete_review.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `review_id=${reviewId}`
        });

        const result = await res.json();

        if (result.status === 'success') {
          const reviewBox = document.querySelector(`.review-box[data-review-id='${reviewId}']`);
          if (reviewBox) reviewBox.remove();
        } else {
          alert(result.message || 'Delete failed.');
        }
      } catch (err) {
        console.error(err);
        alert("Error deleting review.");
      }
    }
  });
});

