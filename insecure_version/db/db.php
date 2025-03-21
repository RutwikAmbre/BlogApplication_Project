<?php
// db.php - Insecure Database Connection
$dir = __DIR__ . '/../db/users.db';

$dsn = "sqlite:".$dir;
$pdo = new PDO($dsn); // No error handling

$createTableQuery = "
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);
";
$pdo->exec($createTableQuery); // Execute the query to create the table

?>