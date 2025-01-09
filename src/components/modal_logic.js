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
const listsSelectElem = document.getElementById("lists");
const addBtn = document.getElementById("addBtn");

openModalBtns.forEach((btn) =>
  btn.addEventListener("click", (e) => {
    loadingIndicator.style.display = "flex";
    modalBox.style.display = "none";
    modalOverlay.style.display = "flex";
    addBtn.removeEventListener("click");

    const fetch1 = fetch(`/AJAX/get_movie.php?id=${e.target.movie_id}`);
    const fetch2 = fetch("/AJAX/get_lists.php");

    Promise.all([fetch1, fetch2])
      .then(([response1, response2]) =>
        Promise.all([response1.json(), response2.json()])
      )
      .then(([data, lists]) => {
        movieTitle.innerHTML = data.title;
        year.innerHTML = data.year;
        director.innerHTML = data.director;
        stars.innerHTML = data.stars;
        genre.innerHTML = data.genres;
        summary.innerHTML = data.summary;
        poster.src = `/assets/posters/${e.target.movie_id}.webp`;
        const newSourceElem = document.createElement("source");
        newSourceElem.src = `/assets/trailers/${e.target.movie_id}.mp4`;
        trailer.replaceChildren(newSourceElem);
        movieID.value = e.target.movie_id;
        lists.forEach((list) => {
          const optionElem = document.createElement("option");
          optionElem.value = list.id;
          optionElem.innerHTML = list.title;
          listsSelectElem.appendChild(optionElem);
        });
      })
      .then(() => {
        addBtn.addEventListener("click", () => {
          fetch(`/AJAX/list_member.php`, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `list_id=${encodeURIComponent(
              listsSelectElem.value
            )}&movie_id=${encodeURIComponent(e.target.movie_id)}`,
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
        loadingIndicator.style.display = "none";
        modalBox.style.display = "flex";
      })
      .catch((error) => {
        alert(error);
        console.error(error);
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
