<?php
session_start();
// Include database connection file
require("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    // Validate that both fields are filled and passwords match
    if (!empty($password) && !empty($confirmPassword) && !empty($email)) {
        if ($password === $confirmPassword) {
            // Check if email already exists
            $stmt2 = $conn->prepare("SELECT uemail FROM users WHERE uemail = ?");
            $stmt2->bind_param("s", $email);
            $stmt2->execute();
            $stmt2->store_result();

            if ($stmt2->num_rows > 0) {
                $_SESSION['errorMessage'] = "Email already in use.";
            } else {
                // Hash the password and insert user data
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (ufname, ulname, upass, uemail) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $fname, $lname, $hashedPassword, $email);
                
                if ($stmt->execute()) {
                    $getuInfo = $conn->prepare("SELECT uid, ufname, ulname from users WHERE uemail = ?");
                    $getuInfo->bind_param("s", $email);
                    if($getuInfo->execute()){
                        $userInfo = $getuInfo->get_result();
                        if ($userInfo->num_rows > 0){
                            $user = $userInfo->fetch_assoc();
                            $cookieExpire = time() + (60 * 24 * 60 * 60);
                            setcookie("id", base64_encode($user["uid"]), $cookieExpire, "/");
                            $_SESSION["fname"] = $user["fname"];
                            $_SESSION["lname"] = $user["lname"];
                            $_SESSION["uemail"] = $email;
                            header("Location: login.php");
                            exit;
                        }
                }
                } else {
                    $_SESSION['errorMessage'] = "Error occurred during registration.";
                }
                $stmt->close();
            }
            $stmt2->close();
        } else {
            $_SESSION['errorMessage'] = "Passwords do not match.";
        }
    } else {
        $_SESSION['errorMessage'] = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Register</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2><br>
        <?php
        if (isset($_SESSION['errorMessage'])) {
            echo "<div style='color: red;'>" . $_SESSION['errorMessage'] . "</div>";
            unset($_SESSION['errorMessage']);
        } elseif (isset($_SESSION['successMessage'])) {
            echo "<div style='color: green;'>" . $_SESSION['successMessage'] . "</div>";
            unset($_SESSION['successMessage']);
        }
        ?>
        <form id="emailForm" method="POST" action="register.php">
            <div class="input-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" placeholder="Enter your first name" required>
            </div>
            <div class="input-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" placeholder="Enter your last name" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <div id="emailStatus"></div>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Choose a password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>
        <?php if (isset($_SESSION['errorMessage'])): ?>
        <p class="error-message"><?php echo $_SESSION['errorMessage']; ?></p>
        <?php unset($_SESSION['errorMessage']);?>
        <?php endif; ?>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
