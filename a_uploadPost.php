<?php
// Connection to the database
require('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize $imageData in case no image is uploaded
    $imageData = null;

    
    // Handling image upload
    if (isset($_FILES['photoInput'])) {
        $image = $_FILES['photoInput'];
        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageError = $image['error'];

        if ($imageError === 0) {
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($imageExtension), $allowedExtensions)) {
                // Generate a unique name for the image
                $newImageName = uniqid('', true) . '.' . $imageExtension;

                // Set the upload path
                $uploadDir = 'media/';
                $uploadPath = $uploadDir . $newImageName;

                // Move the image to the directory
                if (move_uploaded_file($imageTmpName, $uploadPath)) {
                    // Save the image path to the database (if necessary)
                    $imageData = $uploadPath;
                } else {
                    echo "Error uploading the image.";
                    exit;
                }
            } else {
                echo "Invalid image format.";
                exit;
            }
        } else {
            echo "Error uploading image.";
            exit;
        }
    }

    // Handling post submission
    if (isset($_POST['userInput'])) {
        $userInput = $_POST['userInput'];
        $decoded_Cookie = base64_decode($_COOKIE["id"]);

        // Prepare the SQL query to insert the post and the image path
        $stmt = $conn->prepare("INSERT INTO posts (pauthor, ptext, pimage) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $decoded_Cookie, $userInput, $imageData);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Post uploaded successfully!";
        } else {
            echo "Error uploading post.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
