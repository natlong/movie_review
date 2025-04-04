// Navigate to review page
function navigateToReviewPage(movieId) {
  window.location.href = 'reviews.php?movie=' + movieId;
}

let currentFeatured = 0;
let spotlightInterval;
let spotlightPaused = false;

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

function nextFeatured() {
  const cards = document.querySelectorAll(".featured-card");
  if (!cards.length) return;
  const nextIndex = (currentFeatured + 1) % cards.length;
  showFeatured(nextIndex);
}

function prevFeatured() {
  const cards = document.querySelectorAll(".featured-card");
  if (!cards.length) return;
  const prevIndex = (currentFeatured - 1 + cards.length) % cards.length;
  showFeatured(prevIndex);
}

function startSpotlightInterval() {
  spotlightInterval = setInterval(() => {
    if (!spotlightPaused) {
      nextFeatured();
    }
  }, 5000);
}

function stopSpotlightInterval() {
  clearInterval(spotlightInterval);
}

function attachPopcornEventListeners() {
  const popcornIcons = document.querySelectorAll('.movies-wrapper .popcorn-icon');
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
      e.preventDefault();
      e.stopPropagation();
      currentMovieId = icon.getAttribute('data-movie-id');
      const movieTitle = icon.getAttribute('data-movie-title');
      popupMovieTitle.textContent = movieTitle;

      selectedRating = 0;
      popcornImages.forEach(img => img.classList.remove('selected'));

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
        alert(`You rated ${popupMovieTitle.textContent} with ${selectedRating}/5 popcorns!`);
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

document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Spotlight initialized");

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

  // FAQ Toggle
  document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
      const answer = button.nextElementSibling;
      const plus = button.querySelector('.plus');
      const isOpen = answer.classList.contains('open');

      document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
      document.querySelectorAll('.plus').forEach(p => p.textContent = "+");

      if (!isOpen) {
        answer.classList.add('open');
        plus.textContent = "−";
      }
    });
  });

  // Review Form Toggle
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
        setTimeout(() => location.reload(), 1000);
      }
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

function addToLikes(event, movieId, element) {
  event.stopPropagation();
  console.log("Clicked element:", element);
  fetch('add_like.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `movie_id=${movieId}`
  })
  .then(res => res.json())
  .then(data => {
    console.log("✅ Like response:", data);

    if(data.success){
    element.textContent = data.liked ? '❤️' : '♡';
    }
    showPopup(data.message, data.success?'success': 'error');
  })
  .catch(()=>{
    console.error("❌ Like error:", err);

    showPopup("Something went wrong while liking the movie.", "error");

  });
}

function showPopup(message, type) {
  const popup = document.createElement('div');
  popup.className = `watchlist-popup ${type}`;
  popup.textContent = message;
  document.body.appendChild(popup);

  setTimeout(() => popup.remove(), 3000);
}

function sortMovies() {
  const sortValue = document.getElementById("sort-select").value;
  const movieCards = Array.from(document.querySelectorAll(".movie-card"));

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
      case "rating-desc": return ratingB - ratingA;
      case "rating-asc": return ratingA - ratingB;
      case "year-desc": return yearB - yearA;
      case "year-asc": return yearA - yearB;
      default: return 0;
    }
  });

  container.innerHTML = "";
  movieCards.forEach(card => container.appendChild(card));
}

document.addEventListener("DOMContentLoaded", () => {
  const profileToggle = document.getElementById("profile-toggle");
  const profileDropdown = document.getElementById("profile-dropdown");

  if (profileToggle && profileDropdown) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      profileDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", (e) => {
      if (!profileDropdown.contains(e.target) && !profileToggle.contains(e.target)) {
        profileDropdown.classList.add("hidden");
      }
    });
  }
});
