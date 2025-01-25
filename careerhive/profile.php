<?php 
if(!isset($_COOKIE['id'])){
    header("Location: index.php");
    exit;
}
if (isset($_GET['id'])) {
    require('functions.php');
    session_start();
    $user_id = intval(base64_decode($_GET['id']));
    $user_arr = gerUserData($user_id);
}
foreach($user_arr as $user){
echo "<h1>" . htmlspecialchars($user['ufname']) . " " . htmlspecialchars($user['ulname']) . "</h1>";
echo "<img src='data:image/jpeg;base64," . base64_encode($user['uimage']) . "' alt='Profile Image'>";
echo "<p>" . htmlspecialchars($user['utitle']) . "</p>";
}
?>