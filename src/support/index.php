<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./support.css" />
    <title>Support</title>
  </head>
  <body dir="rtl">
    <?php
      require("../components/header.php");
    ?>
    <main>
      <h2>سوالات پرتکرار</h2>

      <div class="accordion">
        <div class="accordion-item">
          <div class="accordion-header">سوال ۱</div>
          <div class="accordion-content">
            <p>
              Lorem ipsum dolor, sit amet consectetur adipisicing elit.
              Molestias, animi cumque reprehenderit dolor laboriosam impedit
              nihil dolore ut debitis deleniti.
            </p>
          </div>
        </div>
        <div class="accordion-item">
          <div class="accordion-header">سوال ۲</div>
          <div class="accordion-content">
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora
              nesciunt illo rerum. Fuga quam quae, iure quasi accusantium in,
              voluptatem rerum sit autem quos natus officia, ipsa commodi. Ea,
              sequi.
            </p>
          </div>
        </div>
        <div class="accordion-item">
          <div class="accordion-header">سوال ۳</div>
          <div class="accordion-content">
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Praesentium ut asperiores nostrum reiciendis nobis atque!
            </p>
          </div>
        </div>

        <div class="accordion-item">
          <div class="accordion-header">سوال ۴</div>
          <div class="accordion-content">
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Praesentium ut asperiores nostrum reiciendis nobis atque!
            </p>
          </div>
        </div>
        <div class="accordion-item">
          <div class="accordion-header">سوال ۵</div>
          <div class="accordion-content">
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Praesentium ut asperiores nostrum reiciendis nobis atque!
            </p>
          </div>
        </div>
      </div>
    </main>

    <script>
      document.querySelectorAll(".accordion-header").forEach((header) => {
        header.addEventListener("click", () => {
          const content = header.nextElementSibling;
          if (content.style.display === "block") {
            content.style.display = "none";
          } else {
            content.style.display = "block";
          }
        });
      });
    </script>
  </body>
</html>
