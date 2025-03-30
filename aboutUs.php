<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - MovieVerse</title>
  <?php include 'inc/head.inc.php'; ?>
  <!-- Ensure Bootstrap CSS is loaded in inc/head.inc.php or here -->
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
        <!-- Left Column: Our Mission -->
        <div class="col-md-6">
          <h2>Our Mission</h2>
          <p>
            At <strong>MovieVerse</strong>, our mission is to provide honest, insightful, and detailed movie reviews that help movie lovers discover the films that resonate with them the most. We strive to create a community-driven platform where all movie enthusiasts can share their opinions, engage with fellow cinephiles, and make informed decisions on their next movie experience. Our reviews are grounded in passion, expertise, and a love for storytelling, ensuring that each film is discussed with the depth and attention it deserves.
          </p>
        </div>
        <!-- Right Column: Our Values -->
        <div class="col-md-6">
          <h2>Our Values</h2>
          <ul>
            <li><strong>Honesty in Reviews:</strong> We strive for transparency in our reviews, offering both positive and negative insights to help our users make informed decisions.</li>
            <li><strong>Community-Driven:</strong> MovieVerse is built by movie lovers, for movie lovers. We value the opinions and contributions of our community.</li>
            <li><strong>Respect for All Opinions:</strong> We believe in fostering an inclusive environment where everyone’s voice is valued, and healthy discussions are encouraged.</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="team my-5">
  <h2 class="text-center mb-4">Meet Our Team</h2>
  <div class="row">
    <!-- Member 1 -->
    <div class="col-md-4 col-sm-6 mb-4">
      <div class="team-member text-center p-3 bg-dark text-white rounded">
        <img src="images/xg.jpg" alt="Tan Xian Guang" class="img-fluid rounded mb-2">
        <h3>Tan Xian Guang</h3>
        <p>Lead Developer</p>
      </div>
    </div>

    <!-- Member 2 -->
    <div class="col-md-4 col-sm-6 mb-4">
      <div class="team-member text-center p-3 bg-dark text-white rounded">
        <img src="images/zining.jpg" alt="Wang Zining" class="img-fluid rounded mb-2">
        <h3>Wang Zining</h3>
        <p>Frontend Developer</p>
      </div>
    </div>

    <!-- Member 3 -->
    <div class="col-md-4 col-sm-6 mb-4">
      <div class="team-member text-center p-3 bg-dark text-white rounded">
        <img src="images/sri.jpg" alt="Ramasubramanian Srinithi" class="img-fluid rounded mb-2">
        <h3>Ramasubramanian Srinithi</h3>
        <p>UI/UX Designer</p>
      </div>
    </div>

    <!-- Member 4 -->
    <div class="col-md-4 col-sm-6 mb-4">
      <div class="team-member text-center p-3 bg-dark text-white rounded">
        <img src="images/natalie.jpg" alt="Long Hui Ying Natalie" class="img-fluid rounded mb-2">
        <h3>Long Hui Ying Natalie</h3>
        <p>Content Writer</p>
      </div>
    </div>

    <!-- Member 5 -->
    <div class="col-md-4 col-sm-6 mb-4">
      <div class="team-member text-center p-3 bg-dark text-white rounded">
        <img src="images/wei wei.jpg" alt="Tay Wei Lin" class="img-fluid rounded mb-2">
        <h3>Tay Wei Lin</h3>
        <p>Marketing Lead</p>
      </div>
    </div>
  </div>
</section>


    <!-- Call To Action Section -->
    <section class="cta my-5 text-center">
      <h2>Join the MovieVerse Community!</h2>
      <p>Sign up today to share your reviews, discover new movies, and engage with fellow cinephiles. Become a part of the growing MovieVerse community!</p>
      <a href="signup.php" class="btn btn-primary">Sign Up</a>
    </section>

    <!-- Contact Section -->
    <section class="contact my-5">
      <h2>Contact Us</h2>
      <p>Email: <a href="mailto:contact@movieverse.com">contact@movieverse.com</a></p>
      <p>Phone: +123 456 7890</p>
    </section>
  </main>

  <?php include 'inc/footer.inc.php'; ?>
  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
