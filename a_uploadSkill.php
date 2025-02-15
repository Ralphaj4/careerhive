<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $id = base64_decode($_COOKIE['id']);
    $type = $input['type'];

    if($type == 1){
        $stmt_getSkillId = $conn->prepare("SELECT skillid FROM skills WHERE skillname = ?");
        $stmt_getSkillId->bind_param("s", $input['skill']);
        $stmt_getSkillId->execute();
        $stmt_getSkillId->bind_result($skill);
        $stmt_getSkillId->fetch();
        $stmt_getSkillId->close();

        $checkSkill = $conn->prepare("SELECT askill from acquired_skills WHERE askill = ?");
        $checkSkill->bind_param("i", $skill);
        if($checkSkill->execute()){
            $skillCount = $checkSkill->get_result();
            if ($skillCount->num_rows > 0){
                exit;
            }
        }

        $stmt = $conn->prepare("INSERT INTO acquired_skills(askill, auser) VALUES (?, ?)");
        $stmt->bind_param("ii", $skill, $id);
        $stmt->execute();
        $stmt->close();
    }
    else if($type == 0){
        $stmt_getSkillId = $conn->prepare("SELECT skillid FROM skills WHERE skillname = ?");
        $stmt_getSkillId->bind_param("s", $input['skill']);
        $stmt_getSkillId->execute();
        $stmt_getSkillId->bind_result($skill);
        $stmt_getSkillId->fetch();
        $stmt_getSkillId->close();

        $stmt = $conn->prepare("DELETE FROM acquired_skills WHERE askill = ? AND auser = ?");
        $stmt->bind_param("ii", $skill, $id);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();
    exit;
}
?>
