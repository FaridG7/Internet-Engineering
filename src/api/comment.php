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
    $movie_id = trim($_POST['movie_id']);
    $rating = trim($_POST['rating']);
    $text = trim($_POST['text']);

    if (empty($movie_id) || empty($rating) || empty($text)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required.']);
        exit;
    }
    try {
        $stmt = $conn->prepare("SELECT * FROM comments WHERE movie_id = ? AND user_id = ?");
        $stmt->bind_param("ss", $movie_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['error' => "you can't add another comment on this movie."]);
            exit;
        }

        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO comments (text, user_id, movie_id, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $text, $_SESSION['user_id'], $movie_id, $rating);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Comment added successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add comment.']);
        }
        $stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }
}
else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    //TODO
}
else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}

?>