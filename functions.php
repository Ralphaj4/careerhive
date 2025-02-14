<?php
// if(!isset($_COOKIE['id'])){
//     header("Location: login.php");
//     exit;
// }

use function PHPSTORM_META\type;

function storeInSession($id){
    require('database.php');
    $getuInfo = $conn->prepare("SELECT ufname, uimage, ulname, uemail, udescription, utitle, ucover from users WHERE uid = ?");
    $getuInfo->bind_param("i", $id);
    if($getuInfo->execute()){
        $userInfo = $getuInfo->get_result();
        if ($userInfo->num_rows > 0){
            $user = $userInfo->fetch_assoc();
            $userInfo->close();
            $_SESSION["fname"] = $user["ufname"];
            $_SESSION["lname"] = $user["ulname"];
            $_SESSION["uimage"] = $user["uimage"];
            $_SESSION["email"] = $user["uemail"];
            $_SESSION["title"] = $user["utitle"];
            $_SESSION["description"] = $user["udescription"];
            $_SESSION["ucover"] = $user["ucover"];
        }
    }
}

function getConnectionCount($id){
    require('database.php');
    $getcount = $conn->prepare("SELECT COUNT(*) AS connection_count FROM connections WHERE cstatus='accepted' AND (csender = ? OR creceiver = ?)");
    $getcount->bind_param("ii", $id, $id);
    if($getcount->execute()){
        $getcount->bind_result($connection_count);
        $getcount->fetch();
        $getcount->close();
        return $connection_count;
    }
}

function getEmployeeCount($id){
    require('database.php');
    $getcount = $conn->prepare("SELECT COUNT(*) AS employee_count FROM work WHERE winstitution= ?");
    $getcount->bind_param("i",$id);
    if($getcount->execute()){
        $getcount->bind_result($employee_count);
        $getcount->fetch();
        $getcount->close();
        return $employee_count;
    }
}

function InsertNotification($sender, $receiver, $type, $postid){
    require('database.php');
    $notif = $conn->prepare("INSERT INTO notifications (senderid, receiverid, ntype, postid) VALUES (?, ?, ?, ?)");
    $notif->bind_param('iisi', $sender, $receiver, $type, $postid);
    $notif->execute();
    $notif->close();
}

function RetrievePosts($id){
    require('database.php');
    $stmt = $conn->prepare("SELECT 
    posts.ptext,
    posts.pimage,
    posts.pid,
    posts.pcreation, 
    users.ufname, 
    users.ulname, 
    users.utitle,
    users.uid,
    users.uimage,
    COUNT(comments.cpost) AS comment_count,
    COUNT(likes.lpost) AS like_count
FROM 
    posts
JOIN 
    users ON posts.pauthor = users.uid
LEFT JOIN 
    likes ON posts.pid = likes.lpost
LEFT JOIN 
    comments ON posts.pid = comments.cpost
JOIN 
    connections ON (connections.csender = users.uid OR connections.creceiver = users.uid) AND connections.cstatus='accepted'
WHERE 
    users.uid != ?
    AND (connections.csender = ? OR connections.creceiver = ?)
GROUP BY 
    posts.pid, users.uid
ORDER BY
    posts.pcreation DESC;
");
    $stmt->bind_param("iii", $id, $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $posts;
}

function getUserImage($id){
    require('database.php');
    $stmt = $conn->prepare("SELECT uimage FROM users WHERE uid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($uimage);
    $result = null;
    if ($stmt->fetch()) {
        $result = $uimage;
    }
    $stmt->close();
    return $result;
}

function getInstImage($id){
    require('database.php');
    $stmt = $conn->prepare("SELECT iimage FROM institutions WHERE iid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($iimage);
    $result = null;
    if ($stmt->fetch()) {
        $result = $iimage;
    }
    $stmt->close();
    return $result;
}

function CheckIfLiked($id, $pid){
    require('database.php');
    $liked = $conn->prepare("SELECT COUNT(*) FROM likes JOIN posts ON ? = likes.lpost WHERE luser= ?");
    $liked->bind_param("ii", $pid, $id);
    if($liked->execute()){
        $liked->bind_result($like_count);
        $liked->fetch();
        $liked->close();
        return $like_count;
    }
}

function getUserData($id){
    require('database.php');
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

function getInstData($id){
    require('database.php');
    $stmt = $conn->prepare("SELECT * FROM institutions WHERE iid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

function handleTime($dateString) {
    $date = new DateTime($dateString);
    $now = new DateTime();
    $diff = $now->diff($date);

    if ($diff->days == 0) {
        if ($diff->h < 24) {
            return $diff->h . " hours ago";
        }
    }
    return $date->format("F jS \a\\t H:i");
}

function IsCompany($email) {
    require('database.php');

    // Prepare statement
    $stmt = $conn->prepare("SELECT iid FROM institutions WHERE iemail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Bind result to variable
    $stmt->bind_result($iid);
    $stmt->fetch();

    // Close statement
    $stmt->close();
    // Return true if an IID was found, otherwise false
    return $iid ? $iid : false;
}

?>