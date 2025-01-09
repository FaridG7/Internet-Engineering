<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./movies.css">
    <title>Movies</title>
</head>

<body dir="rtl">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
        header("Location: /login");
        exit;
    }

    require "../db_connection.php";

    $list_id = $_GET['id'];

    $auth_check = $conn->query("SELECT user_id FROM list WHERE id = " . $list_id);

    if ($auth_check->fetch_assoc()['user_id'] != $_SESSION["user_id"]) {
    ?>
        <h1 style="text-align: center; display:block">
            می‌خوای چی‌کار کنی ناقلا؟
        </h1>
    <?php
    } else {

        $list_members = $conn->query("SELECT movie_id FROM list_member WHERE list_id");
    ?>

        <?php
        require("../components/header.php");
        require("../components/modal.php");
        ?>
        <main>
            <?php
            while ($movie_id = $list_members->fetch_assoc()) {
                echo "<ul>";
                echo '<li class="poster"><button id="openModal" movie_id=' .
                    $movie_id . '><img src="/assets/posters/' .
                    $movie_id . '.webp"/></button></li>';
                echo "</ul>";
            }
            ?>
        </main>
        <script type="module" src="../components/modal_logic.js"></script>
</body>

</html>
<?php
    }
?>