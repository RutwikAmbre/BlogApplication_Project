<?php 
include 'includes/header.php';
require __DIR__ . '/db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed.");
    }

    // Sanitize inputs to prevent parameter manipulation
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Check if password is at least 8 characters long
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long!";
        exit; 
    }

    // Hash the password using PASSWORD_BCRYPT
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare and execute the SQL query using a prepared statement
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        echo "User registered successfully!";
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage()); // Log detailed error
        echo "An error occurred. Please try again later."; // Generic error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <!-- ✅ CSRF Token Field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required minlength="8">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="8">
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>

</body>
</html>
