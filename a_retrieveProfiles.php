<?php
require('database.php');
require_once('functions.php');
mysqli_set_charset($conn, 'utf8mb4');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON input sent by fetch()
    $input = json_decode(file_get_contents("php://input"), true);
    
    // Validate JSON input
    if (!$input) {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Invalid JSON input"]);
        exit;
    }
    
    // Get first and last names from the decoded input
    $fname = $input['fname'] ?? '';
    $lname = $input['lname'] ?? null;
    
    // Optionally, add wildcards for partial matching
    $like_fname = "%$fname%";
    
    if (!empty($lname)) {
        $like_lname = "%$lname%";
        
        $sql = "SELECT uid, ufname, ulname, uimage, udescription  FROM users 
                WHERE ufname LIKE ? OR ulname LIKE ?
                ORDER BY 
                    (ufname LIKE ? AND ulname LIKE ?) DESC,
                    ufname LIKE ? DESC,
                    ulname LIKE ? DESC";
                    
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Database prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("ssssss", $like_fname, $like_lname, $like_fname, $like_lname, $like_fname, $like_lname);
        
    } else {
        $sql = "SELECT uid, ufname, ulname, uimage, udescription FROM users 
                WHERE ufname LIKE ? OR ulname LIKE ?
                ORDER BY 
                    ufname LIKE ? DESC";
                    
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Database prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("sss", $like_fname, $like_fname, $like_fname);
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    $profiles = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Process each profile: convert encoding and attach user image
    foreach ($profiles as &$profile) {
        foreach ($profile as $key => $value) {
            if($key === 'uid'){
                $profile[$key] = urlencode(base64_encode($profile['uid']));
                continue;
            }
            $profile[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
    }

    $response = [
        'profiles' => $profiles,
    ];

    // Set the header for JSON response and output the response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
