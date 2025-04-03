// popcorn.js

let currentMovieId = null;
let selectedRating = 0;

// Open the modal and reset any previous selection
function openRatingModal(elem) {
  currentMovieId = elem.getAttribute('data-movie-id');
  selectedRating = 0;
  // Remove any previously selected rating styles
  document.querySelectorAll('#popcorn-rating-container .popcorn-icon').forEach(function(icon) {
    icon.classList.remove('selected');
  });
  document.getElementById('popcorn-rating-modal').style.display = 'block';
}

// Close the modal
function closeRatingModal() {
  document.getElementById('popcorn-rating-modal').style.display = 'none';
}

// Handle rating selection by highlighting icons up to the chosen value
function selectPopcornRating(elem) {
  selectedRating = parseInt(elem.getAttribute('data-value'));
  let icons = document.querySelectorAll('#popcorn-rating-container .popcorn-icon');
  icons.forEach(function(icon) {
    let value = parseInt(icon.getAttribute('data-value'));
    if (value <= selectedRating) {
      icon.classList.add('selected');
    } else {
      icon.classList.remove('selected');
    }
  });
}

// Submit the rating (you can replace the console log with AJAX to send the rating to your server)
function submitRating() {
  if (selectedRating > 0) {
    console.log("Movie ID " + currentMovieId + " rated " + selectedRating + " popcorn(s).");
    alert("Thank you for rating!");
    closeRatingModal();
  } else {
    alert("Please select a rating.");
  }
}

// Close the modal if the user clicks outside of the modal content
window.onclick = function(event) {
  const modal = document.getElementById('popcorn-rating-modal');
  if (event.target === modal) {
    closeRatingModal();
  }
};
