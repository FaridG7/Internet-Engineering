<style>
  .modalOverlay {
    display: none;
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

  .loadingIndicator {
    background-color: var(--background-color);
    border-radius: 8px;
    width: 90vw;
    height: 90vh;
    padding: 20px;
    text-align: right;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: flex;
  }

  .loadingIndicator h2 {
    text-align: center;
    margin: auto;
  }

  .modalBox {
    background-color: var(--background-color);
    border-radius: 8px;
    width: 90vw;
    height: 90vh;
    padding: 20px;
    text-align: right;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: none;
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

  .poster_section img {
    height: 250px;
    width: auto;
    margin: 1%;
  }

  .poster_section video {
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

  #lists {
    margin-top: auto;
  }

  #list {
    color: black;
  }

  #listBtn {
    background: var(--secondary-color);
  }

  .comment_section {
    width: 100%;
  }

  .comment_section form {
    display: flex;
    flex-direction: column;
  }

  .comment_section form div {
    margin: 1%;
    display: flex;
  }

  .comment_section form select,
  .comment_section form select *,
  .comment_section form textarea {
    color: black;
    padding: 1%;
  }

  .comment_section form select,
  .comment_section form textarea {
    margin: 0px 10px;
  }

  .comment_section form textarea {
    width: 300px;
  }

  .closeBtn,
  .submitBtn {
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
    background: rgb(10, 194, 111);
  }
</style>

<div class="modalOverlay" id="modalOverlay">
  <div class="loadingIndicator" id="loadingIndicator">
    <h2>در حال بارگذاری...</h2>
  </div>
  <div class="modalBox" id="modalBox">
    <div class="description">
      <h3 id="MovieTitle">نام فیلم</h3>
      <p>سال ساخت:&nbsp;<label id="Year">۱۴۰۰</label></p>
      <p>
        کارگردان:&nbsp;
        <label id="Director">
          یه بنده خدا
        </label>
      </p>
      <p>
        ستارگان:&nbsp;<label id="Stars">بنده خدا ۱، بنده خدا ۲، ...</label>
      </p>
      <p id="Genre">ژانر:&nbsp;<label for="">کمدی</label></p>
      <p>خلاصه داستان:&nbsp;<label id="Summary">یکی بود، یکی نبود</label></p>
    </div>
    <div class="poster_section" dir="ltr">
      <img src="" alt="پوستر" id="poster" />
      <video controls id="trailer">
      </video>
      <button class="addBtn" id="addBtn"><span>&#x2795;</span></button>
      <div id="lists" style="display: none;">
        <select id="list"></select>
        <button id="listBtn">اضافه کردن</button>
      </div>
      <script>
        document.getElementById("addBtn").addEventListener("click", () => {
          const listsElem = document.getElementById("lists");
          listsElem.style.display =
            listsElem.style.display === "none" ? "inline" : "none";
        });
      </script>
    </div>
    <div class="comment_section">
      <form action="../AJAX/comment.php" method="post">
        <input type="number" name="movie_id" style="display: none;" id="movieIDinput">
        <div>
          <label>امتیاز شما:</label>
          <select name="rating">
            <option value="5">&#9734;&#9734;&#9734;&#9734;&#9734;</option>
            <option value="4">&#9734;&#9734;&#9734;&#9734;</option>
            <option value="3">&#9734;&#9734;&#9734;</option>
            <option value="2">&#9734;&#9734;</option>
            <option value="1">&#9734;</option>
          </select>
        </div>
        <div>
          <label>نظر شما:</label>
          <textarea name="text" rows="4"></textarea>
        </div>
        <div>
          <button type="submit" class="submitBtn" id="closeModal">ثبت نظر</button>
          <button type="reset" class=" closeBtn" id="closeModal">بستن</button>
        </div>
      </form>
    </div>
  </div>
</div>