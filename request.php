<head>
    <title>Request</title>
    <?php include 'inc/head.inc.php'; ?>

    <body>
        <?php include 'inc/nav.inc.php'; ?>
        <!-- Main Content -->
        <main>
            <header class="page-header">
                <h2>Request a Movie</h2>
                <p>Don't see the movie you're looking for? Request it here, and we’ll add it to our collection!</p>
            </header>

            <section class="request-form-container">
                <form id="request-form">
                    <input type="text" id="request-input" placeholder="Enter movie name" required>
                    <button type="submit">Send Request</button>
                </form>
            </section>

            <section class="recent-requests">
                <h3>Recent Movie Requests</h3>
                <ul>
                    <li>Movie Title 1</li>
                    <li>Movie Title 2</li>
                    <li>Movie Title 3</li>
                </ul>
            </section>
        </main>
        <?php include 'inc/footer.inc.php'; ?>
        <script src="js/request.js"></script>
    </body>
</head>