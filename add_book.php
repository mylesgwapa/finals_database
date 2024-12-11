<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $year_publish = $_POST['year_publish'];
    $num_available = $_POST['num_available'];
    $image_url = $_POST['image_url'] ?? '';

    $stmt = $conn->prepare("INSERT INTO books (title, category, author, year_publish, num_available, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $title, $category, $author, $year_publish, $num_available, $image_url);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
