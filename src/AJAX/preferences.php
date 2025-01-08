<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false || !isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Login needed.']);
    exit;
}

require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}

$input_data = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE || empty($input_data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or empty JSON payload.']);
    exit;
}

$valid_values = ['null', '0', '1'];
$genres_result = $conn->query("SELECT id FROM genres");
if (!$genres_result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error while fetching genres.']);
    exit;
}

$genres = [];
while ($genre = $genres_result->fetch_assoc()) {
    $genres[] = $genre['id'];
}

$stmt_insert = $conn->prepare("INSERT INTO preferences (genre_id, user_id, prefered) VALUES(?,?,?) ON DUPLICATE KEY UPDATE prefered = ?");
$stmt_delete = $conn->prepare("DELETE FROM preferences WHERE user_id = ? AND genre_id = ?");

foreach ($input_data as $genre_id => $preference) {
    if (!in_array($genre_id, $genres) || !in_array($preference, $valid_values)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid genre or preference value.']);
        exit;
    }

    if ($preference !== "null") {
        $stmt_insert->bind_param("ssss", $genre_id, $_SESSION['user_id'], $preference, $preference);
        if (!$stmt_insert->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to apply changes.']);
            exit;
        }
    } else {
        $stmt_delete->bind_param("ss", $_SESSION['user_id'], $genre_id);
        if (!$stmt_delete->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to apply changes.']);
            exit;
        }
    }
}

$stmt_insert->close();
$stmt_delete->close();

http_response_code(200);
echo json_encode(['message' => 'Changes applied successfully.']);
