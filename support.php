<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support - MovieVerse</title>
  <?php include 'inc/head.inc.php'; ?>
  <!-- Ensure Bootstrap CSS is loaded in inc/head.inc.php -->
  <style>
    /* Optional: Custom styles for FAQ toggle, if needed */
    .faq-button {
      width: 100%;
      text-align: left;
      background: none;
      border: none;
      padding: 0;
      font-size: 1.25rem;
      color: #0d6efd; /* Bootstrap primary color */
    }
  </style>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <main class="container mt-5">
    <header class="mb-4">
      <h1>Support &amp; FAQs</h1>
      <p>Find answers to common questions about MovieVerse.</p>
    </header>

    <!-- FAQ Section -->
    <section class="faq-container mb-5">
      <h2>Frequently Asked Questions</h2>
      
      <!-- FAQ Item 1 -->
      <div class="faq mb-3">
        <button class="faq-button" aria-expanded="false" aria-controls="faq1">How do I create an account? ⬇</button>
        <div id="faq1" class="faq-answer mt-2" hidden>
          <p>Click on the "Sign Up" button on the homepage and fill in your details. A confirmation email will be sent to activate your account.</p>
        </div>
      </div>
      
      <!-- FAQ Item 2 -->
      <div class="faq mb-3">
        <button class="faq-button" aria-expanded="false" aria-controls="faq2">How do I submit a movie review? ⬇</button>
        <div id="faq2" class="faq-answer mt-2" hidden>
          <p>Once logged in, go to the movie page and click "Write a Review". Enter your rating and comments, then submit.</p>
        </div>
      </div>
      
      <!-- FAQ Item 3 -->
      <div class="faq mb-3">
        <button class="faq-button" aria-expanded="false" aria-controls="faq3">Can I edit or delete my reviews? ⬇</button>
        <div id="faq3" class="faq-answer mt-2" hidden>
          <p>Yes! Visit your "My Reviews" section, where you can edit or delete your reviews at any time.</p>
        </div>
      </div>
      
      <!-- FAQ Item 4 -->
      <div class="faq mb-3">
        <button class="faq-button" aria-expanded="false" aria-controls="faq4">Are there any guidelines for writing reviews? ⬇</button>
        <div id="faq4" class="faq-answer mt-2" hidden>
          <p>Yes, please keep reviews respectful, spoiler-free, and follow community guidelines. Any inappropriate content may be removed.</p>
        </div>
      </div>
      
      <!-- FAQ Item 5 -->
      <div class="faq mb-3">
        <button class="faq-button" aria-expanded="false" aria-controls="faq5">How do I reset my password? ⬇</button>
        <div id="faq5" class="faq-answer mt-2" hidden>
          <p>Go to the login page and click "Forgot Password?". Enter your email, and we'll send a reset link.</p>
        </div>
      </div>
    </section>

    <!-- FAQ Form Section -->
    <section class="faq-form-container mb-5">
      <h2>Didn't find your question?</h2>
      <p>Submit your question below and our team will get back to you.</p>
      <form class="faq-form" action="#" method="POST" aria-label="Submit your question">
        <div class="mb-3">
          <label for="question" class="visually-hidden">Your Question</label>
          <textarea id="question" name="question" rows="5" class="form-control" placeholder="Insert your question here..." required aria-required="true"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Question</button>
      </form>
    </section>
  </main>

  <?php include 'inc/footer.inc.php'; ?>
  <script src="js/script.js"></script>
  <!-- Bootstrap JS and dependencies (if not already loaded in inc/head.inc.php) -->
  <script>
    // JavaScript to toggle FAQ answers for accessibility.
    document.querySelectorAll('.faq-button').forEach(button => {
      button.addEventListener('click', () => {
        const targetId = button.getAttribute('aria-controls');
        const answer = document.getElementById(targetId);
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', !isExpanded);
        answer.hidden = isExpanded;
      });
    });
  </script>
</body>
</html>
