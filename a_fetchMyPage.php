<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;
    $stmt = $conn->prepare("SELECT 
    jobs.jid,
    jobs.jtitle,
    jobs.jdescription,
    jobs.jcreation
FROM 
    jobs
WHERE 
    jobs.iid = ?
ORDER BY
    jobs.jcreation DESC;
");

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        echo "Error executing query: " . $stmt->error;
        exit;
    }
    $result = $stmt->get_result();
    $jobs = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    $stmt_institutions = $conn->prepare("SELECT institutions.iname, institutions.iimage, institutions.icover,institutions.itype, institutions.iid                                                     
    FROM institutions WHERE iid = ?");
    $stmt_institutions->bind_param("i", $id);
    if (!$stmt_institutions->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_institutions = $stmt_institutions->get_result();
    $institutions = $result_institutions->fetch_all(MYSQLI_ASSOC);
    $stmt_institutions->close();


    $response = [
        'jobs' => $jobs,
        'institution' => $institutions,
        
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
