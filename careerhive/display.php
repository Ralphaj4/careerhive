<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "careerhive";

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch images from the database
$sql = "SELECT uid, uimage FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p>Image ID: " . $row['uid'] . "</p>";
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['uimage']) . '" alt="Image ' . $row['uid'] . '" style="max-width: 300px;"><br><br>';
    }
} else {
    echo "No images found.";
}

$conn->close();
?>
