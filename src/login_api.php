<?php
session_start();

require_once 'db_config.php';

function login($username, $password) {
    global $conn;

    $username = $conn->real_escape_string($username);

    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $conn->close();
            return true;
        }
    }

    $conn->close();
    return false;
}
?>
