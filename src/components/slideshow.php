<?php
  require_once "../db_connection.php";

  $slides_result = $conn->query("SELECT id FROM movies ORDER BY RAND() LIMIT 7;");

  if ($slides_result && $slides_result->num_rows > 0) {

?>
<div class="slideshow-container">
    <?php
        while ($slide = $slides_result->fetch_assoc()) {
          echo '<div class="slide"><button id="openModal"><img src="./assets/posters/'. $slide['id'] .'.webp"/></button></div>'; 
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
