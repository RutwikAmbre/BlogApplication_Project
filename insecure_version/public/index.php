<?php
session_start();
require '../includes/config.php';  // Move one level up to access the 'includes' folder

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use the constant defined in config.php
    $conn = new SQLite3(DB_FILE);
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    
    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $_SESSION['user'] = $row['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid credentials');</script>";
    }
}
?>
