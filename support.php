<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Support & FAQ - MovieVerse</title>
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
  <?php include 'inc/nav.inc.php'; ?>
  
  <!-- NEW: Popup CSS (inline for now) -->
  <style>
    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10000;
    }
    .popup-overlay.hidden {
      display: none !important;
    }
    .popup-content {
      background: #fff !important;
      color: #000 !important;
      padding: 20px 30px;
      border-radius: 8px;
      text-align: center;
      max-width: 400px;
      width: 90%;
      position: relative;
      font-size: 16px;
    }
    .popup-close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      cursor: pointer;
      color: #000 !important;
    }
  </style>
</head>
<body>
  <header>
    <h1>Support & FAQs</h1>
    <p>Find answers to common questions about MovieVerse or drop us a question!</p>
  </header>

  <section class="faq-container">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-item">
      <button class="faq-question">How do I create an account?<span class="plus">+</span></button>
      <div class="faq-answer">
        <p>Click on the "Sign Up" button on the homepage and fill in your details. A confirmation email will be sent to activate your account.</p>
      </div>
    </div>
        
    <div class="faq-item">
      <button class="faq-question">How do I submit a movie review?<span class="plus">+</span></button>
      <div class="faq-answer">
        <p>Once logged in, go to the movie page of the movie of your choice and click "Write a Review". Enter your rating and comments, then submit.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Can I edit or delete my reviews?<span class="plus">+</span></button>
      <div class="faq-answer">
        <p>Yes! Visit your "My Reviews" section, where you can edit or delete your reviews at any time.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">Are there any guidelines for writing reviews?<span class="plus">+</span></button>
      <div class="faq-answer">
        <p>Yes, please keep reviews respectful, spoiler-free, and follow community guidelines. Any inappropriate content may be removed.</p>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question">How do I reset my password?<span class="plus">+</span></button>
      <div class="faq-answer">
        <p>Go to the login page and click "Forgot Password?". Enter your email, and we'll send a reset link.</p>
      </div>
    </div>
  </section>

  <section class="faq-form-container">
    <h2>Didn't find your question?</h2>
    <p>Submit your question below and our team will get back to you.</p>
    <!-- The form posts to support_process.php -->
    <form class="faq-form" action="support_process.php" method="POST" id="faq-form">
      <textarea id="question" name="question" rows="5" placeholder="Insert your support message here..." required></textarea><br>
      <button type="submit">Submit Question</button>
    </form>
  </section>

  <footer>
    <p>Need more help? Contact us at <a href="mailto:support@movieverse.com">support@movieverse.com</a></p>
    <?php include 'inc/footer.inc.php'; ?>
  </footer>

  <!-- NEW: Popup HTML (hidden by default) -->
  <div id="result-popup" class="popup-overlay hidden">
    <div class="popup-content">
      <span class="popup-close" id="popup-close">&times;</span>
      <p id="popup-message"></p>
    </div>
  </div>

  <!-- NEW: Inline JavaScript to display popup based on URL params -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Parse URL parameters
      const urlParams = new URLSearchParams(window.location.search);
      const successMsg = urlParams.get('message');
      const errorMsg = urlParams.get('error');
      const popup = document.getElementById('result-popup');
      const popupMessage = document.getElementById('popup-message');

      if (successMsg || errorMsg) {
        // Set the popup message; decode '+' signs to spaces
        if(successMsg) {
          popupMessage.textContent = decodeURIComponent(successMsg.replace(/\+/g, ' '));
        } else {
          popupMessage.textContent = "Error: " + decodeURIComponent(errorMsg.replace(/\+/g, ' '));
        }
        popup.classList.remove("hidden");
      }

      // Close popup when user clicks the close button
      document.getElementById("popup-close").addEventListener("click", function() {
        popup.classList.add("hidden");
        // Optionally, clear the query string from the URL:
        window.history.replaceState({}, document.title, window.location.pathname);
      });
    });
  </script>
</body>
</html>
