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
    <link rel="stylesheet" href="./styles/support.css" />
    <title>Support</title>
  </head>
  <body dir="rtl">
    <?php
      require("../components/header.php");
    ?>
    <main>
      <form action="../AJAX/prefered.php" method="post" onsubmit="handleFormSubmit(event)">
        <table>
        <thead>
          <th>ژانر</th>
          <th>وضعیت</th>
        </thead>
          <tbody>
            <?php
            while ($genre = $preferences_result->fetch_assoc()) {
              echo '<tr>
                      <td>'.$genre['title'].'</td>
                      <td>
                        <select name="" id="">
                          <option value="N/A" '. ($genre['prefered'] ===  NULL? "selected":"") .'>نامشخص</option>
                          <option value="0"'. ($genre['prefered'] ===  0? "selected":"") .'>بد</option>
                          <option value="1"'. ($genre['prefered'] ===  1? "selected":"") .'>خوب</option>
                        </select>
                      </td>
                    </tr>';
            }
            ?>
          </tbody>
        </table>
        <input type="submit" value="ثبت تغییرات">
      </form>
    </main>
  </body>
</html>
