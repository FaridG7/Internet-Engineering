<style>
.modalOverlay {
  display:none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modalBox {
  background-color: var(--background-color);
  border-radius: 8px;
  width: 90vw;
  height: 90vh;
  padding: 20px;
  text-align: right;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  display: grid;
  grid-template-columns: auto auto auto auto auto auto auto auto auto auto;
  grid-template-rows: auto auto auto auto auto auto auto auto auto auto;
}
.description {
  grid-column: 1 / 5;
  grid-row: 1 / 6;
}
.media {
  grid-column: 5 / 10;
  grid-row: 1 / 6;
}
.opinion {
  grid-column: 1 / 10;
  grid-row: 6 / 10;
}
.opinion form {
  display: grid;
  grid-template-columns: 10% 10% 25% 35%;
  justify-content: space-between;
}
.opinion select {
  color: black;
}
.opinion select * {
  color: black;
  margin-right: 20px;
}
.opinion textarea {
  color: black;
  margin-left: 20%;
}
.bookmarkBtn,
.likeBtn,
.addBtn {
  border: none;
  background: none;
  cursor: pointer;
}
.bookmarkBtn span,
.likeBtn span,
.addBtn span {
  font-size: x-large;
}
.closeBtn {
  grid-column: 1/1;
  grid-row: 10/10;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 16px;
  background: #f44336;
  color: var(--text-color);
  margin-top: 10px;
}
.closeBtn:hover {
  background: #d32f2f;
}
.submitBtn {
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 4px;
  background: var(--primary-color);
  color: var(--text-color);
  margin-top: 10px;
  display: block;
}
.submitBtn:hover {
  background: var(--secondary-color);
}
</style>

<div class="modalOverlay">
  <div class="modalBox">
    <div class="description">
      <h2 id="MovieTitle">نام فیلم</h2>
      <p id="Year"><label for="">سال ساخت: </label>۱۴۰۰</p>
      <p id="Director"><label for="">کارگردان: </label>یه بنده خدا</p>
      <p id="Stars">
        <label for="">ستارگان: </label>بنده خدا ۱، بنده خدا ۲، ...
      </p>
      <p id="Genre"><label for="">ژانر: </label>کمدی</p>
      <p id="Summary"><label for="">خلاصه داستان: </label>یکی بود، یکی نبود</p>
    </div>
    <div class="poster_section" dir="ltr">
      <img src="../assets/posters/28.webp" alt="poster" />
      <video src="">failed to load the video</video>
      <button id="bookmarkBtn"><span>&#9734;</span></button>
      <button id="likeBtn"><span>&#x2764;</span></button>
      <button id="addBtn"><span>&#x2795;</span></button>
    </div>
    <div class="comment_section">
      <form action="">
        <label for="">امتیاز شما:</label>
        <select name="rating" id="ratingInput">
          <option value="5">&#9734;&#9734;&#9734;&#9734;&#9734;</option>
          <option value="4">&#9734;&#9734;&#9734;&#9734;</option>
          <option value="3">&#9734;&#9734;&#9734;</option>
          <option value="2">&#9734;&#9734;</option>
          <option value="1">&#9734;</option>
        </select>
        <label for="">نظر شما:</label>
        <textarea name="text" id="commentText"></textarea>
        <button type="submit" class="submitBtn">ثبت نظر</button>
        <button class="closeBtn" id="closeModal">بستن</button>
      </form>
    </div>
  </div>
</div>
