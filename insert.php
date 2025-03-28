<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'inc/head.inc.php'; ?>
    </head>
    <body>
    <?php include 'inc/nav.inc.php'; ?>
    
    <main class="container mt-5">
    <h1>Insert New Movie</h1>
    <form action="insert_process.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="movie_title">Movie Title</label>
        <input type="text" id="movie_title" name="movie_title" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="movie_description">Description</label>
        <textarea id="movie_description" name="movie_description" class="form-control" rows="4" required></textarea>
      </div>
      <div class="form-group">
        <label for="genre">Genre</label>
        <input type="text" id="genre" name="genre" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="poster">Movie Poster</label>
        <input type="file" id="poster" name="poster" class="form-control-file" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-success">Insert Movie</button>
    </form>
  </main>
        <?php include 'inc/footer.inc.php'; ?>
    </body>
</html>