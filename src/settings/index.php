<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
  header("Location: /login");
  exit;
}

require '../db_connection.php';

$preferences_result = $conn->query("SELECT genre_id, title, prefered FROM genre_preferences WHERE user_id = " . $_SESSION['user_id']);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./settings.css">
  <title>Settings</title>
</head>

<body dir="rtl">
  <?php
  require("../components/header.php");
  ?>
  <main>
    <form>
      <table>
        <thead>
          <th>ژانر</th>
          <th>وضعیت</th>
        </thead>
        <tbody>
          <?php
          while ($genre = $preferences_result->fetch_assoc()) {
            echo '<tr>
                      <td class="label">' . $genre['title'] . '</td>
                      <td class="option">
                        <select name="' . $genre['genre_id'] . '" id="">
                          <option value="null" ' . ($genre['prefered'] ===  NULL ? "selected" : "") . '>نامشخص</option>
                          <option value="0"' . ($genre['prefered'] ===  0 ? "selected" : "") . '>دوست ندارم</option>
                          <option value="1"' . ($genre['prefered'] ===  1 ? "selected" : "") . '>دوست دارم</option>
                        </select>
                      </td>
                    </tr>';
          }
          ?>
        </tbody>
      </table>
      <input type="submit" value="ثبت تغییرات" class="submitBtn">
    </form>
  </main>
  <script>
    document.querySelector("form").addEventListener("submit", (e) => {
      e.preventDefault();
      const selects = document.querySelectorAll("select");

      const formData = {};
      selects.forEach((select) => {
        if (select.name) {
          formData[select.name] = select.value;
        }
      });

      fetch("/AJAX/preferences.php", {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        })
        .then((response) => {
          console.log(response);
          return response.json();
        })
        .then((data) => {
          alert("تغییرات با موفقیت ذخیره شد.");
        })
        .catch((error) => {
          alert(error);
          // alert(`خطایی رخ داد:‌ ${error}`)
        });
    });
  </script>
</body>

</html>