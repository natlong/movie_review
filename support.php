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
  <script src="/js/script.js?v=<?php echo time(); ?>" defer></script> 

</head>
<body>
  <header>
    <h1>Support & FAQs</h1>
    <p>Find answers to common questions about MovieVerse.</p>
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
        <p>Once logged in, go to the movie page and click "Write a Review". Enter your rating and comments, then submit.</p>
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

    <form class="faq-form" action="#" method="POST">
      <textarea id="question" name="question" rows="5" placeholder="Insert your question here..." required></textarea><br>
      <button type="submit">Submit Question</button>
    </form>
  </section>

  <footer>
    <p>Need more help? Contact us at <a href="mailto:support@movieverse.com">support@movieverse.com</a></p>
  </footer>

</body>
</html>
