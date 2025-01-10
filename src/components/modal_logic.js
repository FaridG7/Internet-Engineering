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
const listSelectElem = document.getElementById("list");
const listBtn = document.getElementById("listBtn");

let movie_id = undefined;

listBtn.addEventListener("click", () => {
  fetch(`/AJAX/list_member.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `list_id=${encodeURIComponent(
      listSelectElem.value
    )}&movie_id=${encodeURIComponent(movie_id)}`,
  })
    .then((res) => {
      if (res.ok) {
        alert("فیلم با موفقیت به لیست اضافه شد.");
        location.reload();
      } else alert("خطایی رخ داد.");
    })
    .catch((err) => {
      alert(err);
      console.log(err);
    });
});

openModalBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    movie_id = e.target.getAttribute("movie_id");
    loadingIndicator.style.display = "flex";
    modalBox.style.display = "none";
    modalOverlay.style.display = "flex";

    const fetch1 = fetch(`/AJAX/get_movie.php?id=${movie_id}`);
    const fetch2 = fetch("/AJAX/get_lists.php");

    Promise.all([fetch1, fetch2])
      .then(([response1, response2]) => {
        if (response1.ok && response2.ok)
          return Promise.all([response1.json(), response2.json()]);
        else
          throw new Error(
            `responce1: ${response1.status}, responce2: ${response2.status}`
          );
      })
      .then(([data, lists]) => {
        movieTitle.innerHTML = data.title;
        year.innerHTML = data.year;
        director.innerHTML = data.director;
        stars.innerHTML = data.stars;
        genre.innerHTML = data.genres;
        summary.innerHTML = data.summary;
        poster.src = `/assets/posters/${movie_id}.webp`;
        const newSourceElem = document.createElement("source");
        newSourceElem.src = `/assets/trailers/${movie_id}.mp4`;
        trailer.replaceChildren(newSourceElem);
        movieID.value = movie_id;
        lists.forEach((list) => {
          const optionElem = document.createElement("option");
          optionElem.value = list.id;
          optionElem.innerHTML = list.title;
          listSelectElem.appendChild(optionElem);
        });
      })
      .then(() => {
        loadingIndicator.style.display = "none";
        modalBox.style.display = "flex";
      })
      .catch((error) => {
        alert(error);
        console.error(error);
      });
  });
});

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
