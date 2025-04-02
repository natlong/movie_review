// support_popup.js

document.addEventListener("DOMContentLoaded", function() {
    var faqForm = document.getElementById("faq-form");
    var popup = document.getElementById("login-popup");
    var popupClose = document.getElementById("popup-close");
  
    // When the FAQ form is submitted...
    faqForm.addEventListener("submit", function(e) {
      // isLoggedIn is set in support.php via an inline script
      if (!isLoggedIn) {
        e.preventDefault();           // Prevent form submission
        popup.classList.remove("hidden");  // Show the popup
      }
    });
  
    // Close the popup when the close button is clicked
    popupClose.addEventListener("click", function() {
      popup.classList.add("hidden");
    });
  });
  