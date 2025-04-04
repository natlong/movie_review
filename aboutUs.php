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
    <section class="mission-values-container">
      <!-- Mission Section -->
      <div class="mission-box">
        <h2>Our Mission</h2>
        <p>
          At <strong>MovieVerse</strong>, our mission is to provide honest, insightful, and detailed movie reviews that help movie lovers discover the films that resonate with them the most. We strive to create a community-driven platform where all movie enthusiasts can share their opinions, engage with fellow cinephiles, and make informed decisions on their next movie experience.
        </p>
        <p>
          Founded in 2025 by passionate movie lovers, MovieVerse aims to empower viewers with the tools they need for informed and passionate movie discussions. Whether you are a casual viewer or a seasoned cinephile, we provide a space to share opinions, discover hidden gems, and engage in thought-provoking conversations about cinema.
        </p>
        <p>
          Our reviews are grounded in passion, expertise, and a love for storytelling, ensuring that each film is discussed with the depth and attention it deserves. Through a transparent and community-driven approach, we strive to foster an inclusive space for diverse perspectives, where every opinion is respected and valued.
        </p>
      </div>


      <!-- Values Chart Section -->
      <div class="values-box">
        <h2>Our Values</h2>
        <div class="values-cards">
          <div class="value-card red fade-in">
            <h3>Honesty in Reviews</h3>
            <p>Transparent insights to guide informed decisions.</p>
          </div>
          <div class="value-card yellow fade-in delay-1">
            <h3>Community-Driven</h3>
            <p>Built by movie lovers, for movie lovers.</p>
          </div>
          <div class="value-card green fade-in delay-2">
            <h3>Respect for All Opinions</h3>
            <p>Fostering inclusive, healthy discussion.</p>
          </div>
        </div>
      </div>
    </section>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const animatedCards = document.querySelectorAll(".value-card");

        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.style.animationPlayState = "running";
              observer.unobserve(entry.target); // Animate only once
            }
          });
        }, {
          threshold: 0.3
        });

        animatedCards.forEach(card => {
          card.style.animationPlayState = "paused"; // Pause by default
          observer.observe(card);
        });
      });
    </script>


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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('valuesChart').getContext('2d');
    const valuesChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Honesty in Reviews', 'Community-Driven', 'Respect for All Opinions'],
        datasets: [{
          label: 'Core Values',
          data: [35, 35, 30],
          backgroundColor: [
            '#cc0000', // red
            '#ffcc00', // yellow
            '#4caf50' // green
          ],
          borderColor: '#111',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        animation: {
          duration: 1800, // in ms
          easing: 'easeOutBounce', // animation style
          animateRotate: true,
          animateScale: true
        },
        plugins: {
          legend: {
            labels: {
              color: 'white',
              font: {
                size: 14
              }
            }
          },
          title: {
            display: true,
            text: 'MovieVerse Core Values',
            color: '#fff',
            font: {
              size: 18
            }
          }
        }
      }
    });
  </script>

</body>

</html>