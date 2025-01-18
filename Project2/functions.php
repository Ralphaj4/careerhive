<?php

function storeInSession($id){
    require('database.php');
    $getuInfo = $conn->prepare("SELECT ufname, ulname, uemail from users WHERE uid = ?");
    $getuInfo->bind_param("i", $id);
    if($getuInfo->execute()){
        $userInfo = $getuInfo->get_result();
        if ($userInfo->num_rows > 0){
            $user = $userInfo->fetch_assoc();
            $userInfo->close();
            $_SESSION["fname"] = $user["ufname"];
            $_SESSION["lname"] = $user["ulname"];
            $_SESSION["uemail"] = $user["uemail"];
        }
    }
}

function getConnectionCount($id){
    require('database.php');
    $getcount = $conn->prepare("SELECT COUNT(*) AS connection_count FROM connections WHERE cstatus='accepted' AND csender = ? OR creceiver = ?");
    $getcount->bind_param("ii", $id, $id);
    if($getcount->execute()){
        $getcount->bind_result($connection_count);
        $getcount->fetch();
        $getcount->close();
        return $connection_count;
    }
}

?>