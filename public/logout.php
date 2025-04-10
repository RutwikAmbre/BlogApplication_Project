// logout.php - Insecure Logout
<?php
session_start();

// Improper session termination (session not properly destroyed)

session_unset(); // Clears session variables but doesn't remove session
header('Location: index.php');
exit;
?>
