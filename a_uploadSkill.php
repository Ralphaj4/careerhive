<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $stmt_getLangId = $conn->prepare("SELECT skillid FROM skills WHERE skillname = ?");
    $stmt_getLangId->bind_param("s", $input['skill']);
    $stmt_getLangId->execute();
    $stmt_getLangId->bind_result($skill);
    $stmt_getLangId->fetch();
    $stmt_getLangId->close();

    $checkLanguage = $conn->prepare("SELECT askill from acquired_skills WHERE askill = ?");
    $checkLanguage->bind_param("i", $skill);
    if($checkLanguage->execute()){
        $languageCount = $checkLanguage->get_result();
        if ($languageCount->num_rows > 0){
            exit;
        }
    }

    $id = base64_decode($_COOKIE['id']);

    $stmt = $conn->prepare("INSERT INTO acquired_skills(askill, auser) VALUES (?, ?)");
    $stmt->bind_param("ii", $skill, $id);
    $stmt->execute();
    $stmt->close();
    exit;
}
?>
