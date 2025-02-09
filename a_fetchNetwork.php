<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = base64_decode($_COOKIE['id']);
    $stmt_myConns = $conn->prepare("SELECT
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
    

    $stmt_myConns->bind_param("ii", $id, $id);
    if (!$stmt_myConns->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt_myConns->error]);
        exit;
    }

    $result_myConns = $stmt_myConns->get_result();
    $myConns = $result_myConns->fetch_all(MYSQLI_ASSOC);
    $stmt_myConns->close();

    foreach ($myConns as &$myConn) {
        foreach ($myConn as $key => $value) {
            if($key === 'uid'){
                $myConn[$key] = urlencode(base64_encode($myConn['uid']));
                continue;
            }
        }
    }


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
        echo json_encode(['error' => 'Database error: ' . $stmt_second->error]);
        exit;
    }
    $result_second = $stmt_second->get_result();
    $seconds = $result_second->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_second->close();

    foreach ($seconds as &$second) {
        foreach ($second as $key => $value) {
            if($key === 'uid'){
                $second[$key] = urlencode(base64_encode($second['uid']));
                continue;
            }
        }
    }

    $stmt_incoming = $conn->prepare("SELECT 
    users.uid,
    users.ufname,
    users.ulname,
    users.uemail,
    users.uimage,
    users.ucover,
    users.udescription,
    users.utitle

FROM 
    users 
JOIN 
    connections ON connections.csender = users.uid AND connections.cstatus = 'pending' 
WHERE 
    connections.creceiver = ?");
    $stmt_incoming->bind_param("i", $id);
    if (!$stmt_incoming->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt_incoming->error]);
        exit;
    }
    $result_incomings = $stmt_incoming->get_result();
    $incomings = $result_incomings->fetch_all(MYSQLI_ASSOC);
    $stmt_incoming->close();

    foreach ($incomings as &$incoming) {
        foreach ($incoming as $key => $value) {
            if($key === 'uid'){
                $incoming[$key] = urlencode(base64_encode($incoming['uid']));
                continue;
            }
        }
    }

    // Extract all 'uid' values from incomings and ensure they are the same type
    $incomingUids = array_map('strval', array_column($incomings, 'uid')); // Convert to string

    // Filter out elements from $seconds that exist in $incomingUids
    $seconds = array_filter($seconds, function ($second) use ($incomingUids) {
        return !in_array(strval($second['uid']), $incomingUids, true); // Strict comparison
    });

    // Re-index the array (important)
    $seconds = array_values($seconds);


    $response = [
        'network' => $myConns,
        'seconds' => $seconds,
        'incoming' => $incomings
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    $conn->close();
}
?>
