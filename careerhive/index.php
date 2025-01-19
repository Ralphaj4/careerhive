<?php
// Check if the cookie "id" is set
if (isset($_COOKIE["id"])) {
    header("Location: home.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>
