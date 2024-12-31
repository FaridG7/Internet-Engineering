<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("Location: login.php");
    exit;
}

require 'db_connection.php';

$preferences_sql = "SELECT title, prefered FROM genre_preferences WHERE user_id = " . $_SESSION['user_id'];

$preferences_result = $conn->query($preferences_sql);

$preferd_genres = [];
$nonpreferd_genres = [];
while ($row = $result->fetch_assoc()) {
    if($row['prefered'] == 1){
      $preferd_genres[] = $row;
    }else{
      $nonpreferd_genres[] = $row;
    }
}

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
          <!-- TODO: add preferences and nonpreferences -->

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
          <!-- <li class="list">
            <a href="#">
              <div class="list_view">
                <img src="./assets/posters/1.webp" alt="" />
                <img src="./assets/posters/2.webp" alt="" />
                <img src="./assets/posters/28.webp" alt="" />
                <img src="./assets/posters/15.webp" alt="" />
                <img src="./assets/posters/21.jpg" alt="" />
                <img src="./assets/posters/27.webp" alt="" />
              </div>
              <div class="list_title">
                <label>مورد علاقه‌های من</label>
              </div>
            </a>
          </li> -->
        </ul>
      </div>
      <div class="latest">
        <h3>پیشنهادی‌های شما</h3>
        <ul>
          <!-- <li>
            <a href="#">
              <img src="./assets/posters/22.webp" alt="" />
            </a>
          </li> -->
        </ul>
      </div>
    </main>
    <script type="module" src="./scripts/dashboard.js"></script>
  </body>
</html>
