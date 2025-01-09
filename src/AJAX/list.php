<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("Location: /login");
    http_response_code(302);
    exit;
}

require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
}

$title = trim($_POST['title']);

if (!isset($title) && empty($title)) {
    http_response_code(400);
    exit;
}
try {
    $check_stmt = $conn->prepare("SELECT * FROM list WHERE user_id = ? AND title = ?");
    $check_stmt->bind_param("ss", $_SESSION['user_id'], $title);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        http_response_code(409);
        exit;
    }

    $check_stmt->close();

    $insert_stmt = $conn->prepare("INSERT INTO list (user_id, title) VALUES (?, ?)");
    $insert_stmt->bind_param("ss", $_SESSION['user_id'], $title);

    if ($insert_stmt->execute()) {
        http_response_code(201);
    } else {
        throw new Exception('Failed to insert list.', 1);
    }
    $insert_stmt->close();
} catch (Exception $e) {
    http_response_code(500);
}
