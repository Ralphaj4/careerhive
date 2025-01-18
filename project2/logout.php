<?php
// Start the session
session_start();
setcookie("id", "", time() - 3600, "/");
// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit;
?>
