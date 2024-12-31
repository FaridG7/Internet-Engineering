<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./login.css" />
    <title>Login</title>
  </head>
  <body dir="rtl">
    <div class="container">
      <div class="content">
        <span class="logo">
          <img src="../assets/images/logo.png" alt="" />
        </span>
        <form action="./api.php" method="post" autocomplete="on">
          <h2 class="title">ورود اعضا</h2>
          <input type="text" placeholder="نام کاربری" name="username" />
          <input type="password" placeholder="رمز عبور" name="password" />
          <button>ورود</button>
        </form>
        <p class="footer">عضو نیستید؟ <a href="../register">عضو شوید</a></p>
      </div>
    </div>
  </body>
</html>
