<?php include 'includes/header.php';

require __DIR__ . '/db/db.php';

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed.");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fix SQL Injection with Prepared Statements
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    // Verify the password using password_verify
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php?error=Invalid credentials");
        exit;
    }
}

// Display error message
if (isset($_GET['error'])) {
    echo "<div id='error_message'>Error: " . htmlspecialchars($_GET['error']) . "</div>"; // Escape user input to prevent XSS
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
        <!-- CSRF token field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <input id="username" type="text" name="username" placeholder="Username" required>
        <input id="password" type="password" name="password" placeholder="Password" required>
        <button id="login_button" type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>

</body>
</html>
