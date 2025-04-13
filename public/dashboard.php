<?php include 'includes/header.php';

$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear it after displaying once
}

require __DIR__ . '/db/db.php';

// CSRF token generation and validation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the CSRF token is valid
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed.");
    }

    // Handle new post submission
    if (isset($_POST['title'], $_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $username = $_SESSION['username'];

        // Use prepared statement for post insertion
        $sql = "INSERT INTO posts (title, content, username) VALUES (:title, :content, :username)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':username' => $username]);

        $success_message = "Post created successfully!";
    }

    // Handle post delete (refresh page)
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $username = $_SESSION['username'];

        // Check if the post belongs to the logged-in user using a prepared statement
        $stmt = $pdo->prepare("SELECT username FROM posts WHERE id = :id");
        $stmt->execute([':id' => $delete_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post && $post['username'] == $username) {
            // Use prepared statement for post deletion
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
            $stmt->execute([':id' => $delete_id]);
            $_SESSION['success_message'] = "Post deleted successfully!";
        } else {
            $_SESSION['success_message'] = "You can only delete your own posts.";
        }

        // Redirect to refresh the page
        header("Location: dashboard.php");
        exit();
    }
}

// Fetch posts for the logged-in user
$username = $_SESSION['username']; // Get the current user's username
$stmt = $pdo->prepare("SELECT * FROM posts WHERE username = :username ORDER BY created_at DESC");
$stmt->execute([':username' => $username]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .centered-container {
            max-width: 700px;
            margin: auto;
        }

        .logout {
            float: right;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .post-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
        }

        textarea {
            resize: vertical;
        }
    </style>
</head>
<body>

<div class="container mt-4 centered-container">
    <a class="logout btn btn-danger mb-3" href="logout.php">Logout</a>
    <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <!-- Success Message (Post created/deleted) -->
    <?php if ($success_message): ?>
    <div class="alert alert-success" id="success_message">
        <?php echo htmlspecialchars($success_message); ?>
    </div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" id="createPost" onclick="toggleForm()">Create New Post</button>

    <!-- Hidden Post Form -->
    <div id="postForm" style="display: none;">
        <form method="POST" class="post-form mb-4">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" name="content" rows="4" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Post</button>
        </form>
    </div>

    <!-- Display Posts -->
    <?php foreach ($posts as $post): ?>
        <div class="card" id="post-<?php echo $post['id']; ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p class="text-muted"><small>Posted on <?php echo $post['created_at']; ?></small></p>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('postForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
