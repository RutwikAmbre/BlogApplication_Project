<?php
session_start();
require __DIR__ . '/../db/db.php';  // Assuming your db connection is correct

// Fetch existing posts from the database
$sql = "SELECT * FROM posts";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ3QJq3U2Xh0uYk5y5byfiPf6X2Zyw5OmTg45gs2jqLlhcZybFV92u98K6K6" crossorigin="anonymous">
    <style>
        /* Centering login/register section */
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden; /* Prevent scrolling of posts outside the visible area */
        }
        .container {
            text-align: center;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .btn {
            margin: 10px;
            padding: 10px 20px;
        }
        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        /* Styling for floating posts */
        .post {
            position: absolute;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 200px;
            height: auto;
            max-width: 90%; /* Prevents posts from being too wide */
            box-sizing: border-box;
        }

        .post h4 {
            margin-bottom: 10px;
            color: #333;
        }
        .post p {
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to Blog Application</h1>

    <?php if (isset($_SESSION['username'])): ?>
        <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! You are logged in.</p>
        <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    <?php else: ?>
        <p>You are not logged in.</p>
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="register.php" class="btn btn-secondary">Register</a>
    <?php endif; ?>
</div>

<?php
// Display posts in random positions around the centered login/register section
foreach ($posts as $post):
    // Generate random positions for the post
    $top = rand(5, 60);  // Random percentage for top position (5% to 60%)
    $left = rand(5, 90); // Random percentage for left position (5% to 90%)
?>
    <div class="post" style="top: <?php echo $top; ?>%; left: <?php echo $left; ?>%;">
        <h4><?php echo htmlspecialchars($post['title']); ?></h4>
        <p><?php echo htmlspecialchars($post['content']); ?></p>
    </div>
<?php endforeach; ?>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybKQ4fGvhj7qg2B03E6lmZ/JMCp7NfB0IY6dUnX6TT7FZC0d4" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8Fq7f7C2
