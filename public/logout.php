// logout.php - secure Logout
<?php include 'includes/header.php';

session_unset();  // Unsets all session variables
session_destroy(); // Destroys the session itself

// Regenerating the session ID after destruction for added security
session_regenerate_id(true);

// Redirect to the login page (or home page)
header('Location: index.php');
exit;
?>
