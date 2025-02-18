<?php
require "../db_connection.php";

$genres_result = $conn->query("SELECT id, title FROM genres");
$slides_result = $conn->query("SELECT id FROM movies ORDER BY RAND() LIMIT 10;");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./movies.css">
  <title>Movies</title>
</head>

<body dir="rtl">
  <?php
  require("../components/header.php");
  require("../components/modal.php");
  ?>
  <main>
    <?php
    require("../components/slideshow.php");
    ?>
    <div class="list">
      <?php
      while ($genre = $genres_result->fetch_assoc()) {
        echo "<h3>" . $genre["title"] . "</h3>";
        echo "<ul>";
        $movies_result = $conn->query("SELECT movie_id FROM movie_genres WHERE genre_id =" . $genre['id'] . " LIMIT 10;");
        while ($movie = $movies_result->fetch_assoc()) {
          echo '<li class="poster"><button id="openModal" ><img src="/assets/posters/'
            . $movie['movie_id'] . '.webp" movie_id='
            . $movie['movie_id'] . ' /></button></li>';
        }
        echo "</ul>";
      }
      ?>
    </div>
  </main>
  <script type="module" src="../components/modal_logic.js"></script>
</body>

</html>