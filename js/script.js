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

window.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Spotlight initialized");

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


//support.php
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



//nav bar 
document.addEventListener("DOMContentLoaded", () => {
  const profileToggle = document.getElementById("profile-toggle");
  const profileDropdown = document.getElementById("profile-dropdown");

  if (profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileDropdown.classList.toggle("hidden");

      
    });

    // Category dropdown toggle
document.addEventListener("DOMContentLoaded", () => {
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


    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!profileDropdown.contains(e.target) && !profileToggle.contains(e.target)) {
        profileDropdown.classList.add("hidden");
      }
    });
  }
});

//watch list 
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

//review function

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
    const yearB = parseInt(b.dataset.year || 0);

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

