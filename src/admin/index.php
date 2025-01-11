<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("Location: /login");
    exit;
}

require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $director = trim($_POST['director']);
    $produce_year = $_POST['produce_year'];
    $summary = trim($_POST['summary']);
    $genres = isset($_POST['genres']) && is_array($_POST['genres']) ? array_map('intval', $_POST['genres']) : [];
    $stars = isset($_POST['stars']) && is_array($_POST['stars']) ? array_filter(array_map('trim', $_POST['stars']), fn($star) => !empty($star)) : [];

    if (empty($title) || empty($director)) {
        http_response_code(400);
        exit;
    }
    if (!is_numeric($produce_year) || $produce_year < 1888 || $produce_year > date("Y")) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid production year.']);
        exit;
    }

    try {
        $movie_check_stmt = $conn->prepare("SELECT * FROM movies WHERE title = ?");
        $movie_check_stmt->bind_param("s", $title);
        $movie_check_stmt->execute();
        $movie_check_stmt->store_result();

        if ($movie_check_stmt->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['error' => 'a movie with this title already exists.']);
            exit;
        }

        $movie_check_stmt->close();

        $conn->begin_transaction();

        $movie_insert_stmt = $conn->prepare("INSERT INTO movies (title, director, produce_year, summary) VALUES (?, ?, ?, ?)");
        $movie_insert_stmt->bind_param("ssis", $title, $director, $produce_year, $summary);

        if (!$movie_insert_stmt->execute()) {
            throw new Exception("Failed to insert movie.'", 1);
        }

        $inserted_movie_id = $conn->insert_id;
        if (!$inserted_movie_id || $inserted_movie_id <= 0) {
            throw new Exception("Failed to insert movie.'", 1);
        }

        $movie_insert_stmt->close();

        $genres_insert_stmt = $conn->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
        foreach ($genres as $genre_id) {
            $genres_insert_stmt->bind_param("ii", $inserted_movie_id, $genre_id);

            if (!$genres_insert_stmt->execute()) {
                throw new Exception("Failed to insert genre.", 1);
            }
        }
        $genres_insert_stmt->close();

        $star_insert_stmt = $conn->prepare("INSERT INTO stars (name) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
        $stars_insert_stmt = $conn->prepare("INSERT INTO movie_star (movie_id, star_id) VALUES (?, ?)");
        foreach ($stars as $star) {
            $star_insert_stmt->bind_param("s", $star);
            if (!$star_insert_stmt->execute()) {
                throw new Exception('Failed to insert star.', 1);
            }
            $inserted_star_id = $conn->insert_id;

            $stars_insert_stmt->bind_param("ii", $inserted_movie_id, $inserted_star_id);
            if (!$stars_insert_stmt->execute()) {
                throw new Exception('Failed to insert movie_star row.', 1);
            }
        }
        $star_insert_stmt->close();
        $stars_insert_stmt->close();
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $genres_result = $conn->query("SELECT id, title FROM genres");
    if (!$genres_result || $genres_result->num_rows === 0) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error or no genres found.']);
        exit;
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard</title>
    </head>

    <body>
        <form action="./index.php" method="post" validate><br>
            <input type="text" placeholder="title" name="title" required><br>
            <input type="text" placeholder="director" name="director" required><br>
            <input type="number" placeholder="year" name="produce_year" required><br>
            <textarea name="summary" placeholder="summary" required></textarea><br>
            <select name="genres[]" required multiple>
                <?php
                while ($genre = $genres_result->fetch_assoc()) {
                    echo '<option value="' . $genre['id'] . '">' . $genre['title'] . '</option>';
                }
                ?>
            </select><br>
            <input type="text" placeholder="star 1" name="stars[]"><br>
            <input type="text" placeholder="star 2" name="stars[]"><br>
            <input type="text" placeholder="star 3" name="stars[]"><br>
            <input type="submit" value="add">
        </form>
    </body>

    </html>

<?php
}
?>