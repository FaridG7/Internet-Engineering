<?php
require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $movie_id = trim($_GET['id']);

    if (empty($movie_id)) {
        http_response_code(400);
        exit;
    }

    try {
        $movie_id = $conn->real_escape_string($movie_id);

        $movie_result = $conn->query("SELECT * FROM movies WHERE id =" . $movie_id);
        $genres_result = $conn->query("SELECT genre_id FROM movie_genres WHERE movie_id =" . $movie_id);
        $starts_result = $conn->query("SELECT name FROM stars WHERE movie_id =" . $movie_id);

        if ($movie_result->num_rows > 0) {
            $movie = $result->fetch_assoc();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Movie not found.']);
        }
        $conn->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
?>

<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $movie_id = trim($_GET['id']);

    if (empty($movie_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Movie ID is required.']);
        exit;
    }

    try {
        $movie_id = $conn->real_escape_string($movie_id);

        $movie_query = "SELECT * FROM movies WHERE id = $movie_id";
        $movie_result = $conn->query($movie_query);

        if ($movie_result->num_rows > 0) {
            $movie = $movie_result->fetch_assoc();

            $genres_query = "SELECT g.name FROM genres g 
                             JOIN movie_genres mg ON g.id = mg.genre_id 
                             WHERE mg.movie_id = $movie_id";
            $genres_result = $conn->query($genres_query);

            $genres = [];
            while ($genre = $genres_result->fetch_assoc()) {
                $genres[] = $genre['name'];
            }

            $stars_query = "SELECT s.name FROM stars s WHERE s.movie_id = $movie_id";
            $stars_result = $conn->query($stars_query);

            $stars = [];
            while ($star = $stars_result->fetch_assoc()) {
                $stars[] = $star['name'];
            }

            $response = [
                'title' => $movie['title'],
                'director' => $movie['director'],
                'summary' => $movie['summary'],
                'year' => $movie['produce_date'],
                'genres' => $genres,
                'stars' => $stars
            ];

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Movie not found.']);
        }

        $conn->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
