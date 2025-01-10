<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("Location: /login");
    http_response_code(403);
    echo json_encode(['error' => 'Login needed.']);
    exit;
}

require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $movie_id = $_POST['movie_id'];
        $rating = trim($_POST['rating']);
        $text = trim($_POST['text']);

        if (empty($movie_id) || !is_numeric($movie_id) || empty($rating) || empty($text)) {
            echo $_POST['movie_id'];
            http_response_code(400);
            exit;
        }
        $stmt = $conn->prepare("SELECT * FROM comments WHERE movie_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $movie_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            http_response_code(409);
            exit;
        }

        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO comments (text, user_id, movie_id, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $text, $_SESSION['user_id'], $movie_id, $rating);

        if ($stmt->execute()) {
            http_response_code(201);
        } else {
            http_response_code(500);
        }
        $stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $movie_id = $_GET['movie_id'];

    if (empty($movie_id)) {
        http_response_code(400);
        exit;
    }
    try {
        $stmt = $conn->prepare("SELECT * FROM comments WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result->fetch_assoc());
        } else {
            http_response_code(404);
        }
        $stmt->close();
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
}
