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
            <td>
               ژانر
            </td>
            <td>
              وضعیت
            </td>
          </thead>
          <?php
            while ($genre = $preferences_result->fetch_assoc()) {
              
            }
          ?>
        </table>
      </form>
    </main>
  </body>
</html>
