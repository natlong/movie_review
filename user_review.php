<head>
    <title>User Review</title>    
    <?php include 'includes/head.php'; ?>
    <body>
        <?php include 'includes/nav.php'; ?>
        <!-- Main Section -->
        <header>
            <h1>My Movie Reviews</h1>
            <p>View all your uploaded reviews in one place.</p>
        </header>

        <!-- Reviews Section -->
        <section class="reviews-container">
            <h2>Your Reviews</h2>

            <table>
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Inception</td>
                        <td>⭐⭐⭐⭐⭐</td>
                        <td>Amazing movie with mind-blowing twists!</td>
                        <td>March 15, 2025</td>
                    </tr>
                    <tr>
                        <td>Interstellar</td>
                        <td>⭐⭐⭐⭐⭐</td>
                        <td>One of the best sci-fi movies ever made.</td>
                        <td>March 12, 2025</td>
                    </tr>
                    <tr>
                        <td>Joker</td>
                        <td>⭐⭐⭐⭐</td>
                        <td>Dark, intense, and unforgettable.</td>
                        <td>March 10, 2025</td>
                    </tr>
                </tbody>
            </table>
        </section>
        <?php include 'includes/footer.php'; ?>
    </body>
</head>