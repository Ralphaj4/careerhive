<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = base64_decode($_COOKIE['id']);
    $stmt = $conn->prepare("SELECT 
    jobs.jid, 
    jobs.jtitle, 
    jobs.jdescription, 
    jobs.jcreation, 
    institutions.iname, 
    institutions.iimage 
FROM 
    jobs 
JOIN 
    institutions ON jobs.iid = institutions.iid 
WHERE 
    institutions.iid in (SELECT finstitutions FROM follow WHERE fusers = ?);");
    $stmt->bind_param("i", $id);
    
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $jobs = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();

    $response = [
        'jobs' => $jobs
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
