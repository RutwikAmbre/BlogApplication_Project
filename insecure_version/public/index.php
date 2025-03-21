<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure App</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your existing CSS -->
</head>
<body>

<div class="container">
    <h1>Welcome to Secure Application</h1>

    <?php if (isset($_SESSION['username'])): ?>
        <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! You are logged in.</p>
        <a href="dashboard.php" class="btn">Go to Dashboard</a>
        <a href="logout.php" class="btn">Logout</a>
    <?php else: ?>
        <p>You are not logged in.</p>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    <?php endif; ?>
</div>

</body>
</html>
