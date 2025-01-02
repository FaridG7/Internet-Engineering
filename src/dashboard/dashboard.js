import { URL } from "../API_URL";

const openModalBtns = document.querySelectorAll("#openModal");
const closeModalBtns = document.querySelectorAll("#closeModal");

const modalOverlay = document.getElementById("modalOverlay");
const loadingIndicator = document.getElementById("loadingIndicator");
const modalBox = document.getElementById("modalBox");
const movieTitle = document.getElementById("MovieTitle");
const year = document.getElementById("Year");
const director = document.getElementById("Director");
const stars = document.getElementById("Stars");
const genre = document.getElementById("Genre");
const summary = document.getElementById("Summary");
const poster = document.getElementById("poster");
const trailer = document.getElementById("trailer");
const movieID = document.getElementById("movie_id");

openModalBtns.forEach((btn) =>
  btn.addEventListener("click", (e) => {
    loadingIndicator.style.display = "flex";
    modalBox.style.display = "none";
    modalOverlay.style.display = "flex";

    fetch(URL + `/api/get_movie_api.php?id=${e.target.movie_id}`)
      .then((res) => JSON.parse(res))
      .then((data) => {
        movieTitle.innerHTML = data.title;
        year.innerHTML = data.year;
        director.innerHTML = data.director;
        stars.innerHTML = data.stars;
        genre.innerHTML = data.genres;
        summary.innerHTML = data.summary;
        poster.src = URL + `/assets/posters/${e.target.movie_id}.webp`;
        const newSourceElem = document.createElement("source");
        newSourceElem.src = URL + `/assets/trailers/${e.target.movie_id}.mp4`;
        trailer.replaceChildren(newSourceElem);
        movieID.value = e.target.movie_id;
      })
      .then(() => {
        loadingIndicator.style.display = "none";
        modalBox.style.display = "flex";
      });
  })
);

closeModalBtns.forEach((btn) =>
  btn.addEventListener("click", () => {
    modalOverlay.style.display = "none";
  })
);

modalOverlay.addEventListener("click", (event) => {
  if (event.target === modalOverlay) {
    modalOverlay.style.display = "none";
  }
});
