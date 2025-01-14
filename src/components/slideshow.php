<?php
require_once "../db_connection.php";

$slides_result = $conn->query("SELECT id FROM movies ORDER BY RAND() LIMIT 7;");

if ($slides_result && $slides_result->num_rows > 0) {

?>
  <style>
    .slideshow-container {
      max-width: 900px;
      position: relative;
      margin: auto;
      height: 200px;
      overflow: hidden;
    }

    .slide {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 150px;
      transition: all 0.5s ease-in-out;
      transform-origin: center;
      opacity: 0;
    }

    .active {
      opacity: 1;
      z-index: 2;
      left: 50%;
      transform: translate(-50%, -50%) scale(1);
    }

    .prev-slide {
      opacity: 0.5;
      z-index: 1;
      left: 30%;
      transform: translate(-50%, -50%) scale(0.8);
    }

    .next-slide {
      opacity: 0.5;
      z-index: 1;
      left: 70%;
      transform: translate(-50%, -50%) scale(0.8);
    }

    .slide img {
      width: 100%;
      height: auto;
      border-radius: 5px;
    }

    .prev,
    .next {
      cursor: pointer;
      position: absolute;
      top: 50%;
      width: auto;
      padding: 10px;
      margin-top: -22px;
      color: white;
      font-weight: bold;
      font-size: 18px;
      border: none;
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 3px;
      user-select: none;
      z-index: 3;
    }

    .prev {
      left: 10px;
    }

    .next {
      right: 10px;
    }
  </style>
  <div class="slideshow-container">
    <?php
    while ($slide = $slides_result->fetch_assoc()) {
      echo '<div class="slide"><button><img src="/assets/posters/' . $slide['id'] . '.webp"/></button></div>';
    }
    ?>
    <button class="prev" onclick="changeSlide(-1)">&#10095;</button>
    <button class="next" onclick="changeSlide(1)">&#10094;</button>
  </div>
  <script>
    let currentSlide = 0;
    const slideInterval = 3000;

    const slides = document.querySelectorAll(".slide");

    function showSlide(index) {

      if (index >= slides.length) currentSlide = 0;
      if (index < 0) currentSlide = slides.length - 1;

      slides.forEach((slide) => {
        slide.classList.remove("active", "prev-slide", "next-slide");
      });

      const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
      const nextIndex = (currentSlide + 1) % slides.length;

      slides[currentSlide].classList.add("active");
      slides[prevIndex].classList.add("prev-slide");
      slides[nextIndex].classList.add("next-slide");
    }

    function changeSlide(step) {
      currentSlide += step;
      showSlide(currentSlide);
    }

    window.changeSlide = changeSlide;

    showSlide(currentSlide);

    setInterval(() => {
      currentSlide += 1;
      showSlide(currentSlide);
    }, slideInterval);
  </script>
<?php
}
?>