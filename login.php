<?php
if(isset($_COOKIE['id'])){
    header("Location: home.php");
    exit;
}
// Include database connection file
require("database.php");
require("functions.php");
$errorMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    // Validate email and password
    if (!empty($email) && !empty($password)) {
        // Prepare a statement to prevent SQL injection
        $iid = IsCompany($email);
        if($iid){
            $stmt = $conn->prepare("SELECT ipass FROM institutions WHERE iemail = ?");
        }else{
            $stmt = $conn->prepare("SELECT upass FROM users WHERE uemail = ?");
        }        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        // Verify the password
        if ($hashedPassword && password_verify($password, $hashedPassword)) {
            session_start();
            if($iid){
                $_SESSION['iemail'] = $email;
                $cookieExpire = time() + (60 * 24 * 60 * 60);
                setcookie("id",base64_encode($iid), $cookieExpire, "/");
                setcookie("inst","1", $cookieExpire, "/");
                header("Location: mypage.php");
                exit;
            } else{
                // Login success: start session and redirect to dashboard
                $_SESSION['uemail'] = $email;
                $getUID = $conn->prepare("SELECT uid from users WHERE uemail = ?");
                $getUID->bind_param("s", $email);
                if($getUID->execute()){
                    $getUID->bind_result($uid);
                    $getUID->fetch();
                    $getUID->close();
                    $cookieExpire = time() + (60 * 24 * 60 * 60);
                    setcookie("id",base64_encode($uid), $cookieExpire, "/");
                    $_SESSION["fname"] = $user["fname"];
                    $_SESSION["lname"] = $user["lname"];
                    $_SESSION["uemail"] = $email;
                    header("Location: home.php");
                    exit;
                }
            }
        }
            
    } else {
            $errorMessage = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit">Login</button>
            <?php if ($errorMessage): ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </form>

        <div class="text-container">
    <p>Don't have an account?</p>
    <span class="menu-container">
        <button id="register-btn">Register here</button>
        <div id="dropdown-menu" class="dropdown-menu">
            <a href="register.php">Personal</a>
            <a href="registerInstitution.php">Work</a>
        </div>
    </span>
</div>
    </div>
</body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const button = document.getElementById("register-btn");
            const menu = document.getElementById("dropdown-menu");

            button.addEventListener("click", function (event) {
                event.stopPropagation(); // Prevents the event from bubbling up
                menu.style.display = (menu.style.display === "block") ? "none" : "block";
            });

            // Close the menu when clicking outside
            document.addEventListener("click", function (event) {
                if (!menu.contains(event.target) && event.target !== button) {
                    menu.style.display = "none";
                }
            });
        });
    </script>
</html>