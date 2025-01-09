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

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405);
}

try {
    $result = $conn->query("SELECT id, title FROM list WHERE user_id = " . $_SESSION["user_id"]);
    $lists = [];
    while ($row = $result->fetch_assoc()) {
        $lists[] = $row;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($lists);
} catch (Exception $e) {
    http_response_code(500);
} finally {
    $conn->close();
}
