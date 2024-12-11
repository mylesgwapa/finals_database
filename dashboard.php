<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myles";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching number of users in the last week
$sql_users = "SELECT COUNT(*) AS user_count FROM users WHERE registration_date >= NOW() - INTERVAL 1 WEEK";
$result_users = $conn->query($sql_users);
$user_count = $result_users->fetch_assoc()['user_count'];

// Fetching number of borrowed books in the last week
$sql_borrowed = "SELECT COUNT(*) AS borrowed_count FROM borrowed_books WHERE borrow_date >= NOW() - INTERVAL 1 WEEK";
$result_borrowed = $conn->query($sql_borrowed);
$borrowed_count = $result_borrowed->fetch_assoc()['borrowed_count'];

// Fetching number of reserved books in the last week
$sql_reserved = "SELECT COUNT(*) AS reserved_count FROM reserved_books WHERE reserve_date >= NOW() - INTERVAL 1 WEEK";
$result_reserved = $conn->query($sql_reserved);
$reserved_count = $result_reserved->fetch_assoc()['reserved_count'];

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e8f0f2;
        }
        h1{
            text-align: center;
            font-size: 40px;
        }

        .container {
            width: 85%;
            margin: 30px auto;
            padding-top: 20px;
            text-align: center;
        }

        .stats-block {
            display: inline-block;
            width: 28%;
            padding: 20px;
            margin: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stats-block h2 {
            margin: 0;
            font-size: 2.5em;
            color: #333;
        }

        .stats-block p {
            margin: 10px 0;
            font-size: 1.3em;
            color: #555;
        }

        .legend {
            margin-top: 20px;
            font-size: 1.2em;
            color: #333;
        }

        /* Button Style */
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>Statistics</h1>
    <div class="container">
        <!-- Dashboard Blocks -->
        <div class="stats-block">
            <h2 id="num-users"><?php echo $user_count; ?></h2>
            <p>Total Users (Last Week)</p>
        </div>
        <div class="stats-block">
            <h2 id="num-borrowed"><?php echo $borrowed_count; ?></h2>
            <p>Books Borrowed (Last Week)</p>
        </div>
        <div class="stats-block">
            <h2 id="num-reserved"><?php echo $reserved_count; ?></h2>
            <p>Books Reserved (Last Week)</p>
        </div>

        <!-- Legend -->
        <div class="legend">
            <p><span style="color: #4CAF50;">&#8226;</span> Users</p>
            <p><span style="color: #FF9800;">&#8226;</span> Borrowed Books</p>
            <p><span style="color: #2196F3;">&#8226;</span> Reserved Books</p>
        </div>

        <!-- Back Button -->
        <a href="admin.php" class="back-btn">Back to Admin</a>
    </div>

</body>
</html>
