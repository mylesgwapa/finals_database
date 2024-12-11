<?php
include 'db.php'; // Database connection

// Handle Borrow Action
if (isset($_GET['borrow'])) {
    $book_id = $_GET['borrow'];
    $user_id = 1;  // Set the user_id based on the logged-in user, this is a placeholder for now.
    
    // Check if the book is available
    $check_availability_query = $conn->prepare("SELECT num_available FROM books WHERE id = ?");
    $check_availability_query->bind_param("i", $book_id);
    $check_availability_query->execute();
    $check_availability_result = $check_availability_query->get_result();
    
    if ($check_availability_result->num_rows > 0) {
        $book = $check_availability_result->fetch_assoc();
        
        if ($book['num_available'] > 0) {
            // Proceed with borrowing the book
            $borrow_query = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id) VALUES (?, ?)");
            $borrow_query->bind_param("ii", $user_id, $book_id);
            $borrow_query->execute();
            
            // Decrease the number of available books
            $decrease_availability_query = $conn->prepare("UPDATE books SET num_available = num_available - 1 WHERE id = ?");
            $decrease_availability_query->bind_param("i", $book_id);
            $decrease_availability_query->execute();
            
            // Redirect back to the page after the action
            header("Location: book.php");
            exit;
        } else {
            echo "<script>alert('This book is not available for borrowing.');</script>";
        }
    }
}

// Handle Reserve Action
if (isset($_GET['reserve'])) {
    $book_id = $_GET['reserve'];
    $user_id = 1;  // Set the user_id based on the logged-in user, this is a placeholder for now.
    
    // Proceed with reserving the book
    $reserve_query = $conn->prepare("INSERT INTO reserved_books (user_id, book_id) VALUES (?, ?)");
    $reserve_query->bind_param("ii", $user_id, $book_id);
    $reserve_query->execute();
    
    // Decrease the number of available books
    $decrease_availability_query = $conn->prepare("UPDATE books SET num_available = num_available - 1 WHERE id = ?");
    $decrease_availability_query->bind_param("i", $book_id);
    $decrease_availability_query->execute();
    
    // Redirect back to the page after the action
    header("Location: book.php");
    exit;
}

// Handle Return Action
if (isset($_GET['return'])) {
    $book_id = $_GET['return'];
    $user_id = 1;  // Set the user_id based on the logged-in user, this is a placeholder for now.
    
    // Delete from borrowed_books table
    $return_query = $conn->prepare("DELETE FROM borrowed_books WHERE user_id = ? AND book_id = ?");
    $return_query->bind_param("ii", $user_id, $book_id);
    $return_query->execute();
    
    // Increase the number of available books
    $increase_availability_query = $conn->prepare("UPDATE books SET num_available = num_available + 1 WHERE id = ?");
    $increase_availability_query->bind_param("i", $book_id);
    $increase_availability_query->execute();
    
    // Redirect back to the page after the action
    header("Location: book.php");
    exit;
}

// Handle Unreserve Action
if (isset($_GET['unreserve'])) {
    $book_id = $_GET['unreserve'];
    $user_id = 1;  // Set the user_id based on the logged-in user, this is a placeholder for now.

    // Delete from reserved_books table
    $unreserve_query = $conn->prepare("DELETE FROM reserved_books WHERE user_id = ? AND book_id = ?");
    $unreserve_query->bind_param("ii", $user_id, $book_id);
    $unreserve_query->execute();

    // Increase the number of available books
    $increase_availability_query = $conn->prepare("UPDATE books SET num_available = num_available + 1 WHERE id = ?");
    $increase_availability_query->bind_param("i", $book_id);
    $increase_availability_query->execute();

    // Redirect back to the page after the action
    header("Location: book.php");
    exit;
}

// Fetch all unique categories from the books table
$categories_result = $conn->query("SELECT DISTINCT category FROM books");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Get the selected category from the dropdown
$selected_category = $_GET['category'] ?? 'all';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/book.css">
    <title>Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Books Collection</h1>

        <!-- Category Filter Dropdown -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="category-select">Filter by Category</label>
                        <select class="form-select" id="category-select" name="category" onchange="this.form.submit()">
                            <option value="all" <?= $selected_category == 'all' ? 'selected' : '' ?>>All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>" <?= $selected_category == $category ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <!-- Books Display -->
        <div class="row">
            <?php
            // Query to fetch books based on the selected category
            if ($selected_category == 'all') {
                $books_query = "SELECT * FROM books";
                $books_result = $conn->query($books_query);
            } else {
                $books_query = $conn->prepare("SELECT * FROM books WHERE category = ?");
                $books_query->bind_param("s", $selected_category);
                $books_query->execute();
                $books_result = $books_query->get_result();
            }

            if ($books_result->num_rows > 0) {
                while ($row = $books_result->fetch_assoc()) {
                    echo "
                    <div class='col-md-4 mb-4'>
                        <div class='card h-100'>
                            <img src='{$row['image_url']}' class='card-img-top' alt='{$row['title']}' style='height: 250px; object-fit: cover;' onerror=\"this.onerror=null;this.src='default.png';\">
                            <div class='card-body'>
                                <h5 class='card-title'>{$row['title']}</h5>
                                <p class='card-text'><strong>Author:</strong> {$row['author']}</p>
                                <p class='card-text'><strong>Year:</strong> {$row['year_publish']}</p>
                                <p class='card-text'><strong>Available:</strong> {$row['num_available']}</p>
                                <div class='d-flex justify-content-between'>
                                    <a href='?borrow={$row['id']}' class='btn btn-success btn-sm'>Borrow</a>
                                    <a href='?reserve={$row['id']}' class='btn btn-warning btn-sm'>Reserve</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p class='text-center'>No books found in this category.</p>";
            }
            ?>
        </div>

        <!-- Borrowed Books Section -->
        <h2>Borrowed Books</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $borrowed_query = $conn->prepare("SELECT bb.book_id, b.title, bb.borrow_date FROM borrowed_books bb JOIN books b ON bb.book_id = b.id WHERE bb.user_id = 1");
                $borrowed_query->execute();
                $borrowed_result = $borrowed_query->get_result();

                while ($borrowed = $borrowed_result->fetch_assoc()) {
                    echo "<tr>
                        <td>#</td>
                        <td>{$borrowed['title']}</td>
                        <td>{$borrowed['borrow_date']}</td>
                        <td><a href='?return={$borrowed['book_id']}' class='btn btn-danger btn-sm'>Return</a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Reserved Books Section -->
        <h2>Reserved Books</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Book Title</th>
                    <th>Reserve Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $reserved_query = $conn->prepare("SELECT b.title, rb.book_id, rb.reserve_date FROM reserved_books rb JOIN books b ON rb.book_id = b.id WHERE rb.user_id = 1");
                $reserved_query->execute();
                $reserved_result = $reserved_query->get_result();

                while ($reserved = $reserved_result->fetch_assoc()) {
                    echo "<tr>
                        <td>#</td>
                        <td>{$reserved['title']}</td>
                        <td>{$reserved['reserve_date']}</td>
                        <td><a href='?unreserve={$reserved['book_id']}' class='btn btn-danger btn-sm'>Unreserve</a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
