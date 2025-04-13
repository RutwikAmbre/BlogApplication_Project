<?php
// public/includes/header.php

session_start();

// Security headers
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; object-src 'none';");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog App</title>
</head>
<body></body>