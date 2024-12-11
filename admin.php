<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $title = $_POST['title'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $year_publish = $_POST['year_publish'];
    $num_available = $_POST['num_available'];
    $image_url = $_POST['image_url'] ?? '';

    $stmt = $conn->prepare("INSERT INTO books (title, category, author, year_publish, num_available, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $title, $category, $author, $year_publish, $num_available, $image_url);

    if ($stmt->execute()) {
        // Redirect to the same page to prevent form resubmission
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Page - Book Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Book Management</h1>
        

        <!-- Add Book Form -->
        <form action="admin.php" method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <input type="text" name="title" class="form-control" placeholder="Book Title" required>
                </div>
                <div class="col-md-2 mb-3">
                    <input type="text" name="category" class="form-control" placeholder="Category" required>
                </div>
                <div class="col-md-2 mb-3">
                    <input type="text" name="author" class="form-control" placeholder="Author" required>
                </div>
                <div class="col-md-2 mb-3">
                    <input type="number" name="year_publish" class="form-control" placeholder="Year Published" required>
                </div>
                <div class="col-md-2 mb-3">
                    <input type="number" name="num_available" class="form-control" placeholder="Number Available" required>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" name="image_url" class="form-control" placeholder="Image URL (Optional)">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
            <a href="dashboard.php" class="back-btn">Go To Dashboard</a>
           
        </form>

        <!-- Book List -->
        <h2>Book List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Year Published</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM books");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td><img src='{$row['image_url']}' alt='Book Image' width='50' height='75' onerror=\"this.onerror=null;this.src='default.png';\"></td>
                        <td>{$row['title']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['year_publish']}</td>
                        <td>{$row['num_available']}</td>
                        <td>
                            <a href='edit_book.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_book.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
