<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false || !isset($_SESSION['user_id'])) {
    http_response_code(302);
    header("Location: /login");
    exit;
}

require '../db_connection.php';
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request method.']);
        exit;
    }

    $input_data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input_data) || json_last_error() !== JSON_ERROR_NONE || empty($input_data)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid or empty JSON payload.']);
        exit;
    }

    $genres_result = $conn->query("SELECT id FROM genres");
    if (!$genres_result || $genres_result->num_rows === 0) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error or no genres found.']);
        exit;
    }

    $genres = [];
    while ($genre = $genres_result->fetch_assoc()) {
        $genres[] = $genre['id'];
    }
    $valid_values = ['N/A', 0, 1];

    $stmt_insert = $conn->prepare("INSERT INTO preferences (genre_id, user_id, prefered) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE prefered = ?");
    $stmt_delete = $conn->prepare("DELETE FROM preferences WHERE user_id = ? AND genre_id = ?");

    $errors = [];
    foreach ($input_data as $genre_id => $preference) {

        $genre_id = (int)$genre_id;
        if (!in_array($genre_id, $genres) || !in_array($preference, $valid_values)) {
            $errors[] = "Invalid genre or preference value for genre ID: $genre_id.";
            continue;
        }

        if ($preference !== "N/A") {
            $preference = (int)$preference;
            $stmt_insert->bind_param("iibi", $genre_id, $_SESSION['user_id'], $preference, $preference);
            if (!$stmt_insert->execute()) {
                $errors[] = "Failed to update preference for genre ID: $genre_id.";
            }
        } else {
            $stmt_delete->bind_param("ii", $_SESSION['user_id'], $genre_id);
            if (!$stmt_delete->execute()) {
                $errors[] = "Failed to delete preference for genre ID: $genre_id.";
            }
        }
    }

    $stmt_insert->close();
    $stmt_delete->close();

    if (!empty($errors)) {
        // $log_file = fopen("log.txt", "w") or die("Unable to open file!");
        // foreach ($errors as $error) {
        //     fwrite($log_file, $error);
        // }
        // fclose($log_file);
        throw new Exception("Error Processing Request", 1);
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Changes applied successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
