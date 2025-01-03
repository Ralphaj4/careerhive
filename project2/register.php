<?php
// Include database connection file
include("database.php");

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    // Validate that both fields are filled and passwords match
    if (!empty($username) && !empty($password) && !empty($confirmPassword) && !empty($email)) {
        if ($password === $confirmPassword) {
            // Check if the username already exists in the database
            $stmt = $conn->prepare("SELECT username FROM user WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errorMessage = "Username already taken.";
            } else {

                $stmt2 = $conn->prepare("SELECT useremail FROM user WHERE useremail = ?");
                $stmt2->bind_param("s", $email);
                $stmt2->execute();
                $stmt2->store_result();

                if($stmt2 ->num_rows >0){
                    $errorMessage = "Email already in use";
                }
                else{

                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $useremail = $email;
                // Insert the new user into the database
                $stmt = $conn->prepare("INSERT INTO user (username, password , useremail) VALUES (?, ? , ?)");
                $stmt->bind_param("sss", $username, $hashedPassword ,$useremail);
                if ($stmt->execute()) {
                    header("Location: login.php"); // Redirect to login page after successful registration
                    exit;
                } else {
                    $errorMessage = "Error occurred during registration.";
                }
            }
            }
            $stmt->close();
        } else {
            $errorMessage = "Passwords do not match.";
        }
    } else {
        $errorMessage = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2><br>
        <form method="POST" action="register.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Choose a password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <div class="input-group">
                <label for="useremail">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit">Register</button>
            <?php if ($errorMessage): ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
