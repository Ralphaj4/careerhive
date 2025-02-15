<?php
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $jobId = $_GET['id'];

    $stmt = $conn->prepare("SELECT
    u.ufname,
    u.ulname,
    u.uid,
    u.uimage,
    u.utitle,
    u.uemail,
    a.document,
    j.jtitle
FROM
    users u
JOIN
    applications a ON a.uid = u.uid
JOIN
    jobs j ON j.jid = a.jid
WHERE
    a.jid = ?;

    ");

    $stmt->bind_param('i', $jobId);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $applications = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();

    foreach ($applications as &$application) {
        foreach ($application as $key => $value) {
            if($key === 'uid'){
                $application[$key] = urlencode(base64_encode($application['uid']));
                continue;
            }
        }
    }

    $stmt = $conn->prepare("SELECT jtitle FROM jobs WHERE jid = ?")   ; 
    $stmt->bind_param('i', $jobId);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $jobTitle = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt->close();

    $response = [
        'applications' => $applications,
        'job' => $jobTitle
    ];

    echo json_encode($response);
    
    $conn->close();
}
?>
