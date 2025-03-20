// register.php - Insecure Registration Page
<?php
require __DIR__ . '/../db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; //Storing password in plain text

    $sql = "INSERT INTO users(username, password) VALUES ('$username', '$password')";
    $pdo->query($sql); //SQL Injection vulnerable

    echo "User registered!";
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
