<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // ❌ SQL Injection
    $result = $pdo->query($sql);
    $user = $result->fetch();

    if ($user) {
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        // ❌ Reflected XSS: Displaying user input without escaping
        header("Location: login.php?error=Invalid credentials");
        exit;
    }
}

// Display error message (Reflected XSS vulnerability)
if (isset($_GET['error'])) {
    echo "Error: " . $_GET['error']; // ❌ Directly outputting user input
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>
