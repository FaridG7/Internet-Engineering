<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
      header("Location: login.php");
      exit;
  }

  require 'db_connection.php';

  $genre_preferences_sql = "SELECT title, prefered FROM genre_preferences WHERE user_id = " . $_SESSION['user_id'];

  $preferences_result = $conn->query($genre_preferences_sql);

  $preferd_genres = [];
  $nonpreferd_genres = [];
  while ($row = $preferences_result->fetch_assoc()) {
    if($row['prefered'] == 1){
      $preferd_genres[] = $row;
    }else{
      $nonpreferd_genres[] = $row;
    }
  }


  $lists_sql = "SELECT title, id FROM list WHERE user_id = " . $_SESSION['user_id'];

  $list_result = $conn->query($lists_sql);

  $lists = [];
  while ($row = $list_result->fetch_assoc()) {
    $lists[] = $row;
  }

  $preferredGenresTitles = array_map(function($genre) {
    return $genre->title;
  }, $preferd_genres);

  $nonpreferredGenresTitles = array_map(function($genre) {
    return $genre->title;
  }, $nonpreferd_genres);

  $preferredGenresString = implode(",", $preferredGenresTitles);
  $nonpreferredGenresString = implode(",", $nonpreferredGenresTitles);
  
  $suggested_movies_sql = "
      SELECT DISTINCT mg.movie_id
      FROM movie_genres mg
      JOIN genres g ON mg.genre_id = g.genre_id
      WHERE g.genre_id IN ($preferredGenresString)
      AND g.genre_id NOT IN ($nonpreferredGenresString)
  ";
  
  $suggested_movies_result = $conn->query($suggested_movies_sql);
  $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/dashboard.css" />
    <title>Dashboard</title>
  </head>
  <body dir="rtl">
    <header>
      <a href="./dashboard.html">
        <img src="./assets/images/logo.png" alt="" />
      </a>
      <nav>
        <button id="theme-toggle" aria-label="Toggle Dark Mode">
          <span id="theme-icon" class="icon-sun">☀️</span>
        </button>
        <a href="/movies.html">فیلم‌ها</a>
        <a href="/support.html" class="setting_icon"><span>&#9881;</span></a>
      </nav>
    </header>
    <main>
      <div class="profile_section">
        <div class="profile_picture">
          <img src="./assets/icons/intersect.svg" alt="" />
          <label>
            <?php
              echo $_SESSION['username'];
            ?>
          </label>
        </div>
        <div class="statics">
           <p>ژانر‌هایی که دوست دارید:</p>
           <?php
            if(count($preferd_genres) > 0){
              foreach ($preferd_genres as $row) {
                echo "<span>" . $row['title'] . "</span>";
              }
            }else{
              echo "<h3>_</h3>";
            }
           ?>
           <p>ژانر‌هایی که دوست ندارید:</p>
          <?php
            if(count($nonpreferd_genres) > 0){
              foreach ($nonpreferd_genres as $row) {
                echo "<span>" . $row['title'] . "</span>";
              }
            }else{
              echo "<h3>_</h3>";
            }
          ?>
        </div>
      </div>
      <div class="lists">
        <h3>لیست‌های شما</h3>
        <ul>
          <?php
            foreach ($lists as $list) {
              echo "<li><a href=/list?id=". $list['id'] ."><label>" . $list['title'] . "</label></a></li>";
            }
          ?>
        </ul>
      </div>
      <div class="latest">
        <h3>پیشنهادی‌های شما</h3>
          <?php
            if ($suggested_movies_result->num_rows > 0) {
              echo "<ul>";
              while ($row = $suggested_movies_result->fetch_assoc()) {
                echo '<li><button id="openModal"><img src="./assets/posters/'. $row['movie_id'] .'.webp"/></button></li>'; 
              }
              echo "</ul>";
            }else{
              echo "_";
            }
          ?>
      </div>
    </main>
    <script type="module" src="./scripts/dashboard.js"></script>
  </body>
</html>
