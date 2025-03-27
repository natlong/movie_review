<head>
    <title>Support</title>
    <?php include 'inc/head.inc.php'; ?>   
    
</head>
<body>
    <?php include 'inc/nav.inc.php'; ?>
    <header>
        <h1>Support & FAQs</h1>
        <p>Find answers to common questions about MovieVerse.</p>
    </header>

    <section class="faq-container">
        <h2>Frequently Asked Questions</h2>

        <div class="faq">
            <button class="faq-question">How do I create an account? ⬇</button>
            <div class="faq-answer">
                <p>Click on the "Sign Up" button on the homepage and fill in your details. A confirmation email will be sent to activate your account.</p>
            </div>
        </div>

        <div class="faq">
            <button class="faq-question">How do I submit a movie review? ⬇</button>
            <div class="faq-answer">
                <p>Once logged in, go to the movie page and click "Write a Review". Enter your rating and comments, then submit.</p>
            </div>
        </div>

        <div class="faq">
            <button class="faq-question">Can I edit or delete my reviews? ⬇</button>
            <div class="faq-answer">
                <p>Yes! Visit your "My Reviews" section, where you can edit or delete your reviews at any time.</p>
            </div>
        </div>

        <div class="faq">
            <button class="faq-question">Are there any guidelines for writing reviews? ⬇</button>
            <div class="faq-answer">
                <p>Yes, please keep reviews respectful, spoiler-free, and follow community guidelines. Any inappropriate content may be removed.</p>
            </div>
        </div>

        <div class="faq">
            <button class="faq-question">How do I reset my password? ⬇</button>
            <div class="faq-answer">
                <p>Go to the login page and click "Forgot Password?". Enter your email, and we'll send a reset link.</p>
            </div>
        </div>

    </section>

    <section class="faq-form-container">
        <h2>Didn't find your question?</h2>
        <p>Submit your question below and our team will get back to you.</p>
    
        <form class="faq-form" action="#" method="POST">
            <!-- Textarea with placeholder instead of label -->
            <textarea id="question" name="question" rows="5" placeholder="Insert your question here..." required></textarea><br>
            <button type="submit">Submit Question</button>
        </form>
    </section>   
    <?php include 'inc/footer.inc.php'; ?>
    <script src="js/script.js"></script> 
</body>