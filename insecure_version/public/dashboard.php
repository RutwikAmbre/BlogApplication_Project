<?php
session_start();
setcookie("session_id", session_id(), time() + 3600, "/", "", false, false); // No secure flag
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['user']; ?>!</h2> <!-- Stored XSS Risk -->
    <p>This is your dashboard.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
