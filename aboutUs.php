<<<<<<< Updated upstream
<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
  <title>About Us - MovieVerse</title>
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <main class="container mt-5">
    <header class="mb-4">
      <h1 class="header-title">About Us</h1>
      <p>Welcome to <strong>MovieVerse</strong>, your go-to destination for honest and insightful movie reviews.</p>
    </header>

    <!-- Mission and Values Section -->
    <section class="mission-values my-5">
      <div class="row">
        <!-- Our Mission -->
        <div class="col-md-6">
          <h2>Our Mission</h2>
          <p>
            At <strong>MovieVerse</strong>, our mission is to provide honest, insightful, and detailed movie reviews that help movie lovers discover the films that resonate with them the most. We strive to create a community-driven platform where all movie enthusiasts can share their opinions, engage with fellow cinephiles, and make informed decisions on their next movie experience. Our reviews are grounded in passion, expertise, and a love for storytelling, ensuring that each film is discussed with the depth and attention it deserves.
          </p>
        </div>
        <!-- Our Values -->
        <div class="col-md-6">
          <h2>Our Values</h2>
          <ul>
            <li><strong>Honesty in Reviews:</strong> Transparent insights that help users make informed decisions.</li>
            <li><strong>Community-Driven:</strong> Built by movie lovers, for movie lovers.</li>
            <li><strong>Respect for All Opinions:</strong> Fostering inclusivity and healthy discussion.</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Meet Our Team Section -->
    <section class="team my-5">
      <h2 class="text-center mb-4">Meet Our Team</h2>
      <div class="team-container">
        <div class="team-member">
          <img src="images/xg.jpg" alt="Tan Xian Guang">
          <h3>Tan Xian Guang</h3>
          <p>Lead Developer</p>
        </div>
        <div class="team-member">
          <img src="images/zining.jpg" alt="Wang Zining">
          <h3>Wang Zining</h3>
          <p>Frontend Developer</p>
        </div>
        <div class="team-member">
          <img src="images/sri.jpg" alt="Ramasubramanian Srinithi">
          <h3>Ramasubramanian Srinithi</h3>
          <p>UI/UX Designer</p>
        </div>
        <div class="team-member">
          <img src="images/natalie.jpg" alt="Long Hui Ying Natalie">
          <h3>Long Hui Ying Natalie</h3>
          <p>Content Writer</p>
        </div>
        <div class="team-member">
          <img src="images/wei wei.jpg" alt="Tay Wei Lin">
          <h3>Tay Wei Lin</h3>
          <p>Marketing Lead</p>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta my-5 text-center">
      <h2>Join the MovieVerse Community!</h2>
      <p>Sign up today to share your reviews, discover new movies, and engage with fellow cinephiles. Become a part of the growing MovieVerse community!</p>
      <a href="signup.php" class="btn btn-primary">Sign Up</a>
    </section>

    <!-- Contact Section -->
    <section class="contact my-5 text-center">
      <h2>Contact Us</h2>
      <p>Email: <a href="mailto:contact@movieverse.com">contact@movieverse.com</a></p>
      <p>Phone: +123 456 7890</p>
    </section>
  </main>

  <?php include 'inc/footer.inc.php'; ?>
  <!-- Optional Bootstrap JS if needed -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
=======
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
  <title>About Us - MovieVerse</title>
  <!-- Include head.inc.php here once to avoid duplicate output -->
  <?php include 'inc/head.inc.php'; ?>
</head>
<body>
  <?php include 'inc/nav.inc.php'; ?>

  <main class="container mt-5">
    <header class="mb-4">
      <h1 class="header-title">About Us</h1>
      <p>Welcome to <strong>MovieVerse</strong>, your go-to destination for honest and insightful movie reviews.</p>
    </header>

    <!-- Mission and Values Section -->
    <section class="mission-values my-5">
      <div class="row">
        <!-- Our Mission -->
        <div class="col-md-6">
          <h2>Our Mission</h2>
          <p>
            At <strong>MovieVerse</strong>, our mission is to provide honest, insightful, and detailed movie reviews that help movie lovers discover the films that resonate with them the most. We strive to create a community-driven platform where all movie enthusiasts can share their opinions, engage with fellow cinephiles, and make informed decisions on their next movie experience. Our reviews are grounded in passion, expertise, and a love for storytelling, ensuring that each film is discussed with the depth and attention it deserves.
          </p>
        </div>
        <!-- Our Values -->
        <div class="col-md-6">
          <h2>Our Values</h2>
          <ul>
            <li><strong>Honesty in Reviews:</strong> Transparent insights that help users make informed decisions.</li>
            <li><strong>Community-Driven:</strong> Built by movie lovers, for movie lovers.</li>
            <li><strong>Respect for All Opinions:</strong> Fostering inclusivity and healthy discussion.</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Meet Our Team Section -->
    <section class="team my-5">
      <h2 class="text-center mb-4">Meet Our Team</h2>
      <div class="team-container">
        <div class="team-member">
          <img src="images/xg.jpg" alt="Tan Xian Guang">
          <h3>Tan Xian Guang</h3>
          <p>Backend Developer</p>
        </div>
        <div class="team-member">
          <img src="images/zining.jpg" alt="Wang Zining">
          <h3>Wang Zining</h3>
          <p>Backend Developer</p>
        </div>
        <div class="team-member">
          <img src="images/sri.jpg" alt="Ramasubramanian Srinithi">
          <h3>Ramasubramanian Srinithi</h3>
          <p>Frontend Developer</p>
        </div>
        <div class="team-member">
          <img src="images/natalie.jpg" alt="Long Hui Ying Natalie">
          <h3>Long Hui Ying Natalie</h3>
          <p>Backend Developer</p>
        </div>
        <div class="team-member">
          <img src="images/wei wei.jpg" alt="Tay Wei Lin">
          <h3>Tay Wei Lin</h3>
          <p>Frontend Developer</p>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta my-5 text-center">
      <h2>Join the MovieVerse Community!</h2>
      <p>Sign up today to share your reviews, discover new movies, and engage with fellow cinephiles. Become a part of the growing MovieVerse community!</p>
      <a href="signup.php" class="btn btn-primary">Sign Up</a>
    </section>

    <!-- Contact Section -->
    <section class="contact my-5 text-center">
      <h2>Contact Us</h2>
      <p>Email: <a href="mailto:contact@movieverse.com">contact@movieverse.com</a></p>
      <p>Phone: +123 456 7890</p>
    </section>
  </main>

  <?php include 'inc/footer.inc.php'; ?>
  <!-- Optional Bootstrap JS if needed -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
>>>>>>> Stashed changes
