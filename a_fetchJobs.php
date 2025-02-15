<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $id = base64_decode($_COOKIE['id']);
    $pid = $input['pid'] ?? 0;

    if(!$pid){
        
        $stmt = $conn->prepare("SELECT 
        jobs.jid, 
        jobs.jtitle, 
        jobs.jdescription, 
        jobs.jcreation,
        institutions.iname, 
        institutions.iimage,
        institutions.iid,
        CASE
            WHEN EXISTS (
                SELECT 1 
                FROM applications 
                WHERE applications.jid = jobs.jid AND applications.uid = ?
            ) THEN 'applied'
            ELSE 'apply'
        END AS applied
    FROM 
        jobs 
    JOIN 
        institutions ON jobs.iid = institutions.iid 
    WHERE 
        institutions.iid in (SELECT finstitutions FROM follow WHERE fusers = ?);");
        $stmt->bind_param("ii", $id, $id);
        if (!$stmt->execute()) {
            echo json_encode(['error' => 'Database error: ' . $stmt->error]);
            exit;
        }
        $result = $stmt->get_result();
        $jobs = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
        $stmt->close();

        $stmt_pages = $conn->prepare("SELECT iid, iname, iimage, icover, iemail, itype 
FROM institutions i
WHERE NOT EXISTS (
    SELECT 1 FROM follow f 
    WHERE f.finstitutions = i.iid 
    AND f.fusers = ?
)
ORDER BY RAND() 
LIMIT 20;
");
    $stmt_pages->bind_param("i", $id);
    if (!$stmt_pages->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result = $stmt_pages->get_result();
    $pages = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_pages->close();
    foreach ($pages as &$page) {
        foreach ($page as $key => $value) {
            if($key === 'iid'){
                $page[$key] = urlencode(base64_encode($page['iid']));
                continue;
            }
        }
    }
    $response = [
        'jobs' => $jobs,
        'pages' => $pages
    ];
    }

    else if($pid){
        $stmt = $conn->prepare("SELECT 
        jobs.jid, 
        jobs.jtitle, 
        jobs.jdescription, 
        jobs.jcreation,
        institutions.iname, 
        institutions.iimage,
        institutions.iid,
        CASE
            WHEN EXISTS (
                SELECT 1 
                FROM applications 
                WHERE applications.jid = jobs.jid AND applications.uid = ?
            ) THEN 'applied'
            ELSE 'apply'
        END AS applied
    FROM 
        jobs 
    JOIN 
        institutions ON jobs.iid = institutions.iid 
    WHERE 
    institutions.iid = ?");
    $stmt->bind_param("ii", $id, $pid);
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
    }  
    

    foreach ($jobs as &$job) {
        foreach ($job as $key => $value) {
            if($key === 'iid'){
                $job[$key] = urlencode(base64_encode($job['iid']));
                continue;
            }
        }
    }

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
