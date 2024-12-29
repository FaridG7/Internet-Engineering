<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if (isset($_GET["username"])) {
    require_once 'login_api.php';

    if (login($_GET['username'], $_GET['password'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "نام‌کاربری یا رمز عبور غیر معتبر.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/login.css" />
    <title>Login</title>
  </head>
  <body dir="rtl">
    <div class="container">
      <div class="content">
        <span class="logo">
          <img src="./assets/images/logo.png" alt="" />
        </span>
        <form action="">
          <h2 class="title">ورود اعضا</h2>
          <?php if ($error): ?>
            <h3><?php echo $error; ?></h3>
          <?php endif; ?>
          <input type="text" placeholder="نام کاربری" name="username" />
          <input type="password" placeholder="رمز عبور" name="password" />
          <button>ورود</button>
        </form>
        <p class="footer">عضو نیستید؟ <a href="./register.html">عضو شوید</a></p>
      </div>
    </div>
  </body>
</html>
