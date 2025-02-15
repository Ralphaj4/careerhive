<?php
session_start();
// Include database connection file
require("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);


    // Validate that all fields are filled and passwords match
    if (!empty($password) && !empty($confirmPassword) && !empty($email) && !empty($name) && !empty($type)) {
        if ($password === $confirmPassword) {
            // Check if email already exists
            $stmt2 = $conn->prepare("SELECT iemail FROM institutions WHERE iemail = ?");
            $stmt2->bind_param("s", $email);
            $stmt2->execute();
            $stmt2->store_result();

            if ($stmt2->num_rows > 0) {
                $_SESSION['errorMessage'] = "Email already in use.";
            } else {
                // Hash the password and insert institution data
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO institutions (iname, itype, iemail, ipass) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $type, $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    $_SESSION['successMessage'] = "Registration successful! Please log in.";
                    header("Location: login.php");
                    exit;
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
    <title>Institution Registration</title>
</head>
<body>
    <div class="register-container">
        <h2>Register Your Institution</h2><br>
        <?php
        if (isset($_SESSION['errorMessage'])) {
            echo "<div style='color: red;'>" . $_SESSION['errorMessage'] . "</div>";
            unset($_SESSION['errorMessage']);
        } elseif (isset($_SESSION['successMessage'])) {
            echo "<div style='color: green;'>" . $_SESSION['successMessage'] . "</div>";
            unset($_SESSION['successMessage']);
        }
        ?>
        <form method="POST" action="registerInstitution.php">
            <div class="input-group">
                <label for="name">Institution Name</label>
                <input type="text" id="name" name="name" placeholder="Enter institution name" required>
            </div>
            <div class="input-group">
            <label>Institution Type</label>
                <div class="radio-group">
                    <label for="company">
                        <input type="radio" id="company" name="type" value="company" required> Company
                    </label>
                    <label for="college">
                        <input type="radio" id="college" name="type" value="college" required> College
                    </label>
                </div>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter institution email" required>
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
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
