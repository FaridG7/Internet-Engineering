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
  display: flex;
  flex-wrap: wrap;
  overflow-y: scroll;
}
.description {
  width: 30%;
  height: 50%;
}

.poster_section {
  display: flex;
  flex-direction: row;
  width: 70%;
  height: 50%;
}
.poster_section img{
  height: 250px;
  width: auto;
  margin: 1%;
}
.poster_section video{
  width: 320px;
  height: 250px;
  margin: 1%;
}
.addBtn {
  margin-top: auto;
  margin-bottom: 1%;
  border: none;
  background: none;
  cursor: pointer;
}
.addBtn span {
  font-size: x-large;
}

.comment_section {
  width: 100%;
}
.comment_section form {
  display: flex;
  flex-direction: column;
}
.comment_section form div{
  margin: 1%;
  display: flex;
}
.comment_section form select,
.comment_section form select *,
.comment_section form textarea {
  color: black;
}
.comment_section form select,
.comment_section form textarea {
  margin: 0px 10px;
}
.comment_section form textarea {
  width: 300px;
}
.closeBtn,.submitBtn {
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 16px;
  color: var(--text-color);
  margin: 0px 20px;
}
.closeBtn {
  background: #f44336;
}
.closeBtn:hover {
  background: #d32f2f;
}
.submitBtn {
  background: var(--primary-color);
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
      <video controls>
        ویدیوی موردنظر یافت نشد.
      </video>
      <button class="addBtn"><span>&#x2795;</span></button>
    </div>
    <div class="comment_section">
      <form action="">
        <div>
          <label for="">امتیاز شما:</label>
          <select name="rating" id="ratingInput">
            <option value="5">&#9734;&#9734;&#9734;&#9734;&#9734;</option>
            <option value="4">&#9734;&#9734;&#9734;&#9734;</option>
            <option value="3">&#9734;&#9734;&#9734;</option>
            <option value="2">&#9734;&#9734;</option>
            <option value="1">&#9734;</option>
          </select>
        </div>
        <div>
          <label for="">نظر شما:</label>
          <textarea name="text" id="commentText" rows="4"></textarea>
        </div>
        <div>
          <button type="submit" class="submitBtn">ثبت نظر</button>
          <button class="closeBtn" id="closeModal">بستن</button>
        </div>
      </form>
    </div>
  </div>
</div>
