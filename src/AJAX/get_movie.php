<?php
require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}

try {
    $movie_id = $_GET['id'];

    if (empty($movie_id) || !is_numeric($movie_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Movie ID is required.']);
        exit;
    }

    $movie_id = $conn->real_escape_string($movie_id);

    $movie_result = $conn->query("SELECT * FROM movies WHERE id = $movie_id");

    if ($movie_result->num_rows > 0) {
        $movie = $movie_result->fetch_assoc();

        $genres_result = $conn->query("SELECT g.title FROM genres g 
                             JOIN movie_genres mg ON g.id = mg.genre_id 
                             WHERE mg.movie_id = $movie_id");

        $genres = [];
        while ($genre = $genres_result->fetch_assoc()) {
            $genres[] = $genre['title'];
        }

        $stars_result = $conn->query("SELECT name FROM movie_star JOIN stars ON stars.id = movie_star.star_id WHERE movie_id = $movie_id");

        $stars = [];
        while ($star = $stars_result->fetch_assoc()) {
            $stars[] = $star['name'];
        }

        $response = [
            'title' => $movie['title'],
            'director' => $movie['director'],
            'summary' => $movie['summary'],
            'year' => $movie['produce_year'],
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
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
