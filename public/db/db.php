<?php include 'includes/header.php';
// db.php - secure Database Connection
$dir = __DIR__ . '/../db/users.db';

$dsn = "sqlite:".$dir;
$pdo = new PDO($dsn);

$createTableQuery = "
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);
";
$pdo->exec($createTableQuery);

?>