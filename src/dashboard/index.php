<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
      header("Location: /login");
      exit;
  }

  require '../db_connection.php';

  $genre_preferences_sql = "SELECT genre_id, title, prefered FROM genre_preferences WHERE user_id = " . $_SESSION['user_id'];

  $preferences_result = $conn->query($genre_preferences_sql);

  $preferd_genres = [];
  $nonpreferd_genres = [];
  while ($row = $preferences_result->fetch_assoc()) {
    if($row['prefered'] === 1){
      $preferd_genres[] = $row;
    }else if($row['prefered'] === 0){
      $nonpreferd_genres[] = $row;
    }
  }

  $lists_sql = "SELECT title, id FROM list WHERE user_id = " . $_SESSION['user_id'];

  $list_result = $conn->query($lists_sql);

  $lists = [];
  while ($row = $list_result->fetch_assoc()) {
    $lists[] = $row;
  }

  $preferredGenresIDs = array_map(function($genre) {
    return $genre->genre_id;
  }, $preferd_genres);

  $nonpreferredGenresIDs = array_map(function($genre) {
    return $genre->genre_id;
  }, $nonpreferd_genres);

  if (empty($preferredGenresIds)) {
    $preferredGenresString = "NULL";
  } else {
    $preferredGenresString = implode(",", $preferredGenresIds);
  }

  if (empty($nonpreferredGenresIds)) {
    $nonpreferredGenresString = "NULL";
  } else {
    $nonpreferredGenresString = implode(",", $nonpreferredGenresIds);
  }


  $suggested_movies_sql = "
      SELECT DISTINCT mg.movie_id
      FROM movie_genres mg
      JOIN genres g ON mg.genre_id = g.id
      WHERE g.id IN ($preferredGenresString)
      AND g.id NOT IN ($nonpreferredGenresString)
  ";
  
  $suggested_movies_result = $conn->query($suggested_movies_sql);
  $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./dashboard.css" />
    <title>Dashboard</title>
  </head>
  <body dir="rtl">
    <?php
      require("../components/header.php");
      require("../components/modal.php");
    ?>
    <main>
      <div class="profile_section">
        <div class="profile">
          <img src="../assets/icons/intersect.svg" alt="" />
          <label>
            <?php
              echo $_SESSION['username'];
            ?>
          </label>
        </div>
        <div class="preferred_genres">
          <div>

            <p>ژانر‌هایی که دوست دارید:&nbsp;</p>
            <?php
            if(count($preferd_genres) > 0){
              foreach ($preferd_genres as $row) {
                echo "<span>" . $row['title'] . "</span>";
              }
            }else{
              echo "<span>-</span>";
            }
            ?>
            </div>
            <div>

              <p>ژانر‌هایی که دوست ندارید:&nbsp;</p>
              <?php
            if(count($nonpreferd_genres) > 0){
              foreach ($nonpreferd_genres as $row) {
                echo "<span>" . $row['title'] . "</span>";
              }
            }else{
              echo "<span>-</span>";
            }
            ?>
            </div>
        </div>
      </div>
      <div class="lists">
        <h3>لیست‌های شما</h3>
          <?php
          if(count($lists) > 0){
            echo "<ul>";
            foreach ($lists as $list) {
              echo "<li><a href=/list?id=". $list['id'] ."><label>" . $list['title'] . "</label></a></li>";
            }
            echo "</ul>";
          }else {
            echo "<span>لیستی یافت نشد.</span>";
          }
            ?>
      </div>
      <div class="suggestion_section">
        <h3>پیشنهادی‌های شما</h3>
          <?php
            if ($suggested_movies_result->num_rows > 0) {
              echo "<ul>";
              while ($row = $suggested_movies_result->fetch_assoc()) {
                echo '<li class="poster"><button id="openModal" movie_id='. $row['movie_id'] .'><img src="./assets/posters/'. $row['movie_id'] .'.webp"/></button></li>'; 
              }
              echo "</ul>";
            }else{
              echo "<span>فیلم پیشنهادی یافت نشد.(بخش ژانرهای مورد علاقه‌ را به روز کنید)</span>";
            }
          ?>
      </div>
    </main>
    <script type="module" src="../components/modal_logic.js"></script>
  </body>
</html>
