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

    $valid_values = ['N/A', '0', '1'];
    
    $genres_result = $conn->query("SELECT id FROM genres");
    while ($genre = $genres_result->fetch_assoc()) {
        if (!isset($input_data[$genre['id']]) || !in_array($input_data[$genre['id']], $valid_values)) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request.']);
            exit; 
        }
    }
    try {
        $stmt = $conn->prepare("DELETE FROM preferences WHERE user_id = ?");
        $stmt->bind_param("s",$_SESSION['user_id']);
    
        if ($stmt->execute()) {
            $stmt->close();
            foreach ($input_data as $genre_id => $preference) {
                if($preference !== "N/A"){
                    $stmt = $conn->prepare("INSERT INTO preferences (genre_id, user_id, prefered) VALUES(?,?,?)");
                    $stmt->bind_param("sss", $genre_id, $_SESSION['user_id'], $preference);
                    
                    if ($stmt->execute()) {
                        http_response_code(201);
                        echo json_encode(['message' => 'Changes applied successfully.']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Failed to apply changes.']);
                    }
                    $stmt->close();
                }
            }
        } else {
            $stmt->close();
            http_response_code(500);
            echo json_encode(['error' => 'Failed to apply changes.']);
        }
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