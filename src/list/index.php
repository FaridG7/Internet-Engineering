<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./list.css">
    <title>List</title>
</head>

<body dir="rtl">

    <?php
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

        $list_members = $conn->query("SELECT movie_id FROM list_member WHERE list_id= $list_id");
    ?>

        <?php
        require("../components/header.php");
        require("../components/modal.php");
        ?>
        <main>
            <?php
            if ($list_members->num_rows > 0) {
                echo "<ul>";
                while ($list_member = $list_members->fetch_assoc()) {
                    echo '<li class="poster"><button id="openModal"><img src="/assets/posters/'
                        . $list_member['movie_id'] . '.webp"  movie_id='
                        . $list_member['movie_id'] . ' /></button></li>';
                }
                echo "</ul>";
            } else {
                echo '<h2>این لیست خالی است.</h2>';
            }
            ?>
        </main>
        <script type="module" src="../components/modal_logic.js"></script>
</body>

</html>
<?php
    }
?>