<?php
// db.php - Insecure Database Connection
$dsn = "sqlite:database.db";
$pdo = new PDO($dsn); // No error handling
?>