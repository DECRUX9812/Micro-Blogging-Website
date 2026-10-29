<?php
$servername = "localhost";
$username = "rpk158";
$password = "ritesh";
$dbname = "rpk158";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Escape a value for safe output into HTML.
function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}
?>
