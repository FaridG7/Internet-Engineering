<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    http_response_code(403);
    echo json_encode(['error' => 'Login needed.']);
    exit;
}

require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input_data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($input_data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or empty JSON payload.']);
        exit;
    }
    

    $valid_values = ['null', '0', '1'];
    
    $genres_result = $conn->query("SELECT id FROM genres");
    while ($genre = $genres_result->fetch_assoc()) {
        if (!isset($input_data[$genre['id']]) || !in_array($input_data[$genre['id']], $valid_values)) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request.']);
            exit; 
        }
    }
    try {
        $delete_stmt = $conn->prepare("DELETE FROM preferences WHERE user_id = ? AND genre_id = ?");
        $insert_stmt = $conn->prepare("INSERT INTO preferences (genre_id, user_id, prefered) VALUES(?,?,?) ON DUPLICATE KEY UPDATE prefered = ?");

        foreach ($input_data as $genre_id => $preference) {
            if($preference !== "null"){
                $insert_stmt->bind_param("ssss", $genre_id, $_SESSION['user_id'], $preference, $preference);
                if (!$insert_stmt->execute()) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to apply changes.']);
                    $insert_stmt->close();
                    exit;
                }
            }else{
                $delete_stmt->bind_param("ss",$_SESSION['user_id'], $genre_id);
                if (!$delete_stmt->execute()) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to apply changes.']);
                    $delete_stmt->close();
                    exit;
                }
            }
        }
        http_response_code(200);
        echo json_encode(['message' => 'Changes applied successfully.']);
        $delete_stmt->close();
        $insert_stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }
}
else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}

?>