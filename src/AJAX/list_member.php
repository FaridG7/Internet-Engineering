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
    echo json_encode(['error' => 'Invalid request method.']);
}

$list_id = $_POST['list_id'];
$movie_id = $_POST['movie_id'];

if (empty($list_id) || empty($movie_id) || !is_numeric($list_id) || !is_numeric($movie_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'title field is required.']);
    exit;
}

try {
    $auth_check_result = $conn->query("SELECT user_id FROM list WHERE id = " . $list_id);

    if ($auth_check_result->fetch_assoc()['user_id'] !== $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'This list belongs to another user.']);
        exit;
    }

    $check_stmt = $conn->prepare("SELECT * FROM list_member WHERE list_id = ? AND movie_id = ?");
    $check_stmt->bind_param("ii", $list_id, $movie_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Movie is already in the list.']);
        exit;
    }

    $check_stmt->close();

    $insert_stmt = $conn->prepare("INSERT INTO list_member (list_id, movie_id) VALUES (?, ?)");
    $insert_stmt->bind_param("ii", $list_id, $movie_id);

    if ($insert_stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Movie added to the list successfully.']);
    } else {
        throw new Exception('Failed to add to list.', 1);
    }
    $insert_stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
