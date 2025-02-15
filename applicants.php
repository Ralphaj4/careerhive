<?php
session_start();

// Include database and functions
require('database.php');
require('functions.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/logo.ico"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>CareerHive</title>
</head>
<body data-page="applicants">
    <?php 
     include('navbarInst.php');
    ?>

    <div class="applicants-container">
        <h1 id="job-header">Applicants for </h1>
    </div>
<script src="scripts.js"></script>
</body>
</html>