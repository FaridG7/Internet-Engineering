<?php
$servername = "mysql";
$username = "popcorn";
$password = "popcorn20";
$dbname = "popcorn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
