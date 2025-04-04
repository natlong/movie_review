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
document.querySelectorAll(".edit-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const id = btn.dataset.id;
    const form = document.querySelector(`.edit-form[data-id='${id}']`);
    form.classList.remove("hidden");
    btn.style.display = "none";
  });
});

document.querySelectorAll(".cancel-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const form = btn.closest(".edit-form");
    form.classList.add("hidden");
    const id = form.dataset.id;
    document.querySelector(`.edit-btn[data-id='${id}']`).style.display = "inline-block";
  });
});

document.querySelectorAll(".edit-form").forEach(form => {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const id = form.dataset.id;

    const res = await fetch("edit_review.php", {
      method: "POST",
      body: formData
    });

    const text = await res.text();
    if (text.trim() === "success") {
      location.reload();
    } else {
      alert("Update failed: " + text);
    }
  });
});

function addToLikes(event, movieId, element) {
  event.stopPropagation();
  console.log("Adding like for movie ID:", movieId);
  fetch('add_like.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `movie_id=${movieId}`
      })
  .then(res => res.json())
  .then(data => {
    console.log(data);
    if(data.success){
    element.textContent = data.liked ? '❤️' : '♡';
    }
    showPopup(data.message, data.success?'success': 'error');
  })
  .catch(()=>{
    showPopup("Something went wrong while liking the movie.", "error");

  });
}



document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.edit-review-btn').forEach(button => {
    button.addEventListener('click', () => {
      const reviewId = button.dataset.reviewId;
      const form = document.getElementById(`edit-form-${reviewId}`);
      const display = document.getElementById(`review-text-${reviewId}`);

      // Toggle visibility
      if (form.style.display === 'none') {
        form.style.display = 'block';
        display.style.display = 'none';
      } else {
        form.style.display = 'none';
        display.style.display = 'block';
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
        const response = await fetch('update_review.php', {
          method: 'POST',
          body: formData,
        });

        const result = await response.json();

        if (result.status === 'success') {
          const textDisplay = document.getElementById(`review-text-${reviewId}`);
          const ratingDisplay = document.getElementById(`review-rating-${reviewId}`);

          // Update the text and rating on the page
          textDisplay.innerText = formData.get('review_text');
          ratingDisplay.innerText = `⭐ ${parseFloat(formData.get('rating')).toFixed(1)}`;

          form.style.display = 'none';
          textDisplay.style.display = 'block';
        }

        resultBox.textContent = result.message;
        resultBox.className = `edit-result ${result.status}`;
      } catch (error) {
        resultBox.textContent = 'An error occurred. Please try again.';
        resultBox.className = 'edit-result error';
      }
    });
  });
});
