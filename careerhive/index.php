<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loading.css">
    <title>CareerHive</title>
</head>
<body>
    <div class="logo">
        <img src="images/logo.png">
    </div>
    <div class="loader"></div>
</body>
</html>

<?php
if (isset($_COOKIE["id"])) {
    header("refresh:3;home.php");
    exit;
} else {
    header("refresh:3;login.php");
    exit;
}
?>
