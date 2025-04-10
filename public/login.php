<?php
session_start();
require __DIR__ . '/db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; //  SQL Injection possibility
    $result = $pdo->query($sql);
    $user = $result->fetch();

    if ($user) {
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        //  Reflected XSS: Displaying user input without escaping
        header("Location: login.php?error=Invalid credentials");
        exit;
    }
}

// Display error message (Reflected XSS vulnerability)
if (isset($_GET['error'])) {
    echo "Error: " . $_GET['error']; // Directly outputting user input
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>

</body>
</html>
