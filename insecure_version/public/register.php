// register.php - Insecure Registration Page
<?php
require 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new SQLite3('db/users.db');
    $query = "INSERT INTO users (username, password) VALUES ('\$username', '\$password')"; // SQL Injection & Plaintext Password Storage
    
    if ($conn->exec($query)) {
        echo "<script>alert('Registration successful!'); window.location='index.php';</script>"; // Reflected XSS
    } else {
        echo "<script>alert('Error registering user');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
