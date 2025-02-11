<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $start = (int) $input['start'];
    $end = $input['end'];
    $id = base64_decode($_COOKIE['id']);
    $type = $input['type'];

    $stmt_getMajorId = $conn->prepare("SELECT mid FROM majors WHERE mname = ?");
    $stmt_getMajorId->bind_param("s", $input['major']);
    $stmt_getMajorId->execute();
    $stmt_getMajorId->bind_result($major);
    $stmt_getMajorId->fetch();
    $stmt_getMajorId->close();

    $stmt_getSchoolId = $conn->prepare("SELECT iid FROM institutions WHERE iname = ?");
    $stmt_getSchoolId->bind_param("s", $input['school']);
    $stmt_getSchoolId->execute();
    $stmt_getSchoolId->bind_result($school);
    $stmt_getSchoolId->fetch();
    $stmt_getSchoolId->close();

    $checkmajor = $conn->prepare("SELECT * from major WHERE mmajor = ? AND muser = ? AND minstitution = ? AND YEAR(mstart) = ? AND YEAR(mend) = ? AND mtype = ?");
    $checkmajor->bind_param("siiiss", $major, $id, $school, $start, $end, $type);
    if($checkmajor->execute()){
        $majorCount = $checkmajor->get_result();
        if ($majorCount->num_rows > 0){
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO major(mmajor, muser, minstitution, mstart, mend, mtype) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiss", $major, $id, $school, $start, $end, $type);
    $stmt->execute();
    $stmt->close();
    exit;
}
?>
