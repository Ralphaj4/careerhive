<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = base64_decode($_COOKIE['id']);
    $stmt_MyConn = $conn->prepare("SELECT
    users.ufname,
    users.ulname,
    users.uid,
    users.uemail,
    users.uimage,
    users.udescription,
    users.ucover,
    users.utitle
FROM 
    users
JOIN 
    connections ON 
    (connections.csender = users.uid OR connections.creceiver = users.uid)
    AND connections.cstatus = 'accepted'
WHERE 
    ? IN (connections.csender, connections.creceiver)
    AND users.uid != ?;
");
    

    $stmt_MyConn->bind_param("ii", $id, $id);
    if (!$stmt_MyConn->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }

    $result_myConn = $stmt_MyConn->get_result();
    $myConn = $result_myConn->fetch_all(MYSQLI_ASSOC);
    $stmt_MyConn->close();


    $stmt_second = $conn->prepare("WITH FirstDegree AS (
    SELECT 
        CASE 
            WHEN connections.csender = ? THEN connections.creceiver 
            ELSE connections.csender 
        END AS connection_id
    FROM connections
    WHERE ? IN (connections.csender, connections.creceiver) 
    AND connections.cstatus = 'accepted')

SELECT DISTINCT users.ufname,
                users.ulname,
                users.uid,
                users.uemail,
                users.uimage,
                users.udescription,
                users.ucover,
                users.utitle
FROM users
JOIN connections ON 
    (connections.csender = users.uid OR connections.creceiver = users.uid)
    AND connections.cstatus = 'accepted'
JOIN FirstDegree ON 
    FirstDegree.connection_id IN (connections.csender, connections.creceiver)
WHERE 
    users.uid != ? 
    AND users.uid NOT IN (SELECT connection_id FROM FirstDegree);
");
    $stmt_second->bind_param("iii", $id, $id, $id);
    if (!$stmt_second->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_second = $stmt_second->get_result();
    $second = $result_second->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_second->close();

    $response = [
        'network' => $myConn,
        'second' => $second
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
