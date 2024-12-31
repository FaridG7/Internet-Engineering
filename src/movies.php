<?php
  require "db_connection.php";

  $genres_result = $conn->query("SELECT id, title FROM genres");
  $slides_result = $conn->query("SELECT id FROM movies ORDER BY RAND() LIMIT 10;");
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/movies.css" />
    <title>Movies</title>
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
        <a href="./movies.html">فیلم‌ها</a>
        <a href="./series.html">سریال‌ها</a>
        <a href="#" class="setting_icon"><span>&#9881;</span></a>
      </nav>
    </header>

    <div id="modalOverlay">
      <div id="modalBox">
        <div class="description">
          <h2 id="MovieTitle">نام فیلم</h2>
          <p id="Year"><label for="">سال ساخت: </label>۱۴۰۰</p>
          <p id="Director"><label for="">کارگردان: </label>یه بنده خدا</p>
          <p id="Stars">
            <label for="">ستارگان: </label>بنده خدا ۱، بنده خدا ۲، ...
          </p>
          <p id="Genre"><label for="">ژانر: </label>کمدی</p>
          <p id="Summary">
            <label for="">خلاصه داستان: </label>یکی بود، یکی نبود
          </p>
        </div>
        <div id="media" dir="ltr">
          <img src="./assets/posters/28.webp" alt="poster" />
          <video src="">failed to load the video</video>
          <button id="bookmarkBtn"><span>&#9734;</span></button>
          <button id="likeBtn"><span>&#x2764;</span></button>
          <button id="addBtn"><span>&#x2795;</span></button>
        </div>
        <div id="opinion">
          <form action="">
            <label for="">امتیاز شما:</label>
            <select name="" id="">
              <option value="5">&#9734;&#9734;&#9734;&#9734;&#9734;</option>
              <option value="4">&#9734;&#9734;&#9734;&#9734;</option>
              <option value="3">&#9734;&#9734;&#9734;</option>
              <option value="2">&#9734;&#9734;</option>
              <option value="1">&#9734;</option>
            </select>
            <label for="">نظر شما:</label>
            <textarea name="" id=""></textarea>
            <button type="submit" class="submitBtn">ثبت نظر</button>
            <button class="closeBtn" id="closeModal">بستن</button>
          </form>
        </div>
      </div>
    </div>

    <main>
      <div class="slideshow-container">
      <?php
          while ($slide = $slides_result->fetch_assoc()) {
            echo '<li><button id="openModal"><img src="./assets/posters/'. $slide['movie_id'] .'.webp"/></button></li>'; 
          }
        ?>
        <div class="slide">
          <img src="./assets/posters/10.webp" alt="Slide 1" />
        </div>
        <!-- Navigation buttons -->
        <button class="prev" onclick="changeSlide(-1)">&#10095;</button>
        <button class="next" onclick="changeSlide(1)">&#10094;</button>
      </div>
      <div class="list">
        <?php
          while($genre = $genres_result->fetch_assoc()){
            echo "<h3>".$genre["title"]."</h3>";
            echo "<ul>";
            $movies_result = $conn->query("SELECT movie_id FROM movie_genres WHERE genre_id =".$genre['id']." LIMIT 10;");
            while ($movie = $movies_result->fetch_assoc()) {
              echo '<li><button id="openModal"><img src="./assets/posters/'.
               $movie['movie_id'] 
               .'.webp"/></button></li>'; 
            }
            echo "</ul>";
          }
        ?>
      </div>
    </main>
    <script type="module" src="./scripts/movies.js"></script>
  </body>
</html>
