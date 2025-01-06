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
