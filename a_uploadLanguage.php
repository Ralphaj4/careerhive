<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $stmt_getLangId = $conn->prepare("SELECT lid FROM languages WHERE lname = ?");
    $stmt_getLangId->bind_param("s", $input['language']);
    $stmt_getLangId->execute();
    $stmt_getLangId->bind_result($language);
    $stmt_getLangId->fetch();
    $stmt_getLangId->close();

    $checkLanguage = $conn->prepare("SELECT languageid from acquired_languages WHERE languageid = ?");
    $checkLanguage->bind_param("i", $language);
    if($checkLanguage->execute()){
        $languageCount = $checkLanguage->get_result();
        if ($languageCount->num_rows > 0){
            exit;
        }
    }

    $id = base64_decode($_COOKIE['id']);

    $stmt = $conn->prepare("INSERT INTO acquired_languages(languageid, userid) VALUES (?, ?)");
    $stmt->bind_param("ii", $language, $id);
    $stmt->execute();
    $stmt->close();
    exit;
}
?>
