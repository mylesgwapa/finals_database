<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $year_publish = $_POST['year_publish'];
        $num_available = $_POST['num_available'];
        $image_url = $_POST['image_url'];

        $stmt = $conn->prepare("UPDATE books SET title = ?, category = ?, author = ?, year_publish = ?, num_available = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("sssissi", $title, $category, $author, $year_publish, $num_available, $image_url, $id);

        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        $result = $conn->query("SELECT * FROM books WHERE id = $id");
        $book = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Book</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="title" value="<?php echo $book['title']; ?>" required>
        <input type="text" name="category" value="<?php echo $book['category']; ?>" required>
        <input type="text" name="author" value="<?php echo $book['author']; ?>" required>
        <input type="number" name="year_publish" value="<?php echo $book['year_publish']; ?>" required>
        <input type="number" name="num_available" value="<?php echo $book['num_available']; ?>" required>
        <input type="text" name="image_url" value="<?php echo $book['image_url']; ?>">
        <button type="submit">Update</button>
    </form>
</body>
</html>
