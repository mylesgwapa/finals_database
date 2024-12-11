<?php
$conn = new mysqli("localhost", "root", "", "myles");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            echo "<script>alert('Login successful!');</script>";
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post">
            <label>Student ID:</label>
            <input type="text" name="student_id" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <a href="landing.php" class="navigate-btn">Login</a>

        </form>
        <p>Don't have an account?</p>
        <a href="index.php"><button class="navigate-btn">Create Account</button></a>
    </div>
</body>
</html>
