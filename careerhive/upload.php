<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "careerhive";

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an image is uploaded
if (isset($_FILES['profile_image'])) {
    $imageData = file_get_contents($_FILES['profile_image']['tmp_name']);
    $imageType = $_FILES['profile_image']['type'];

    // Validate file type
    if (substr($imageType, 0, 5) == "image") {
        $stmt = $conn->prepare("UPDATE users SET uimage = ? WHERE uid = ?");
        $x = 3;
        $stmt->bind_param("bi", $imageData, $x);
        $stmt->send_long_data(0, $imageData);

        if ($stmt->execute()) {
            echo "Image uploaded successfully!";
        } else {
            echo "Error uploading image: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Please upload a valid image file.";
    }
} else {
    echo "No image uploaded.";
}

$conn->close();
?>
