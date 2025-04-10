<?php
session_start();
require __DIR__ . '/db/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username']; // Get the current user's username

// Handle new post submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Insert post with the current user's username
    $sql = "INSERT INTO posts (title, content, username) VALUES ('$title', '$content', '$username')";
    $pdo->exec($sql);
}

// Handle post update
if (isset($_POST['edit_id'], $_POST['edit_title'], $_POST['edit_content'])) {
    $id = $_POST['edit_id'];
    $title = $_POST['edit_title'];
    $content = $_POST['edit_content'];

    // Check if the post belongs to the logged-in user 
    $stmt = $pdo->prepare("SELECT username FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && $post['username'] == $username) {
        // Update the post
        $sql = "UPDATE posts SET title = '$title', content = '$content' WHERE id = $id";
        $pdo->exec($sql);
    } else {
        echo "You can only edit your own posts.";
    }
}

// Handle post delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Check if the post belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT username FROM posts WHERE id = ?");
    $stmt->execute([$delete_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && $post['username'] == $username) {
        // Delete the post
        $sql = "DELETE FROM posts WHERE id = $delete_id";
        $pdo->exec($sql);

        header("Location: dashboard.php");
        exit;
    } else {
        echo "You can only delete your own posts.";
    }
}

// Fetch posts for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM posts WHERE username = ? ORDER BY created_at DESC");
$stmt->execute([$username]);
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

    <button class="btn btn-primary mb-3" onclick="toggleForm()">Create New Post</button>

    <!-- Hidden Post Form -->
    <div id="postForm" style="display: none;">
        <form method="POST" class="post-form mb-4">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" name="content" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Post</button>
        </form>
    </div>

    <!-- Display Posts -->
    <?php foreach ($posts as $post): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p class="text-muted"><small>Posted on <?php echo $post['created_at']; ?></small></p>
                <div class="d-flex gap-2">
                    <a href="?delete_id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                    <button 
                        class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal"
                        onclick="fillEditForm(
                            <?php echo $post['id']; ?>,
                            '<?php echo htmlspecialchars(addslashes($post['title'])); ?>',
                            `<?php echo htmlspecialchars(addslashes($post['content'])); ?>`
                        )">Edit</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
              <label for="edit_title" class="form-label">Title</label>
              <input type="text" class="form-control" name="edit_title" id="edit_title" required>
          </div>
          <div class="mb-3">
              <label for="edit_content" class="form-label">Content</label>
              <textarea class="form-control" name="edit_content" id="edit_content" rows="4" required></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('postForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function fillEditForm(id, title, content) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_content').value = content;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
