<?php
// db.php: Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "myles";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
