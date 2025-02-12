<?php
require('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;
    $stmt_posts = $conn->prepare("SELECT * from posts WHERE pauthor = ? ");
    

    $stmt_posts->bind_param("i", $id);
    if (!$stmt_posts->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }

    $result_posts = $stmt_posts->get_result();
    $posts = $result_posts->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_posts->close();

    $stmt_user = $conn->prepare("SELECT users.ufname, users.ulname, users.uimage, users.ucover, users.udescription, users.utitle 
    FROM users WHERE uid = ?");
    $stmt_user->bind_param("i", $id);
    if (!$stmt_user->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_user = $stmt_user->get_result();
    $users = $result_user->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_user->close();

    $stmt_education = $conn->prepare("SELECT 
    major.mstart, major.mend, major.mtype,
    institutions.iname, institutions.iimage, majors.mname
FROM 
    users
JOIN
    major ON users.uid = major.muser
JOIN
    institutions ON institutions.iid = major.minstitution
JOIN
    majors ON major.mmajor = majors.mid
WHERE 
    uid = ? AND institutions.itype='college'");
    $stmt_education->bind_param("i", $id);
    if (!$stmt_education->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_education = $stmt_education->get_result();
    $educations = $result_education->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_education->close();

    $stmt_experience = $conn->prepare("SELECT 
    work.wstarted, work.wended, institutions.iimage,
    institutions.iname, work.title
FROM 
    users
JOIN
    work ON users.uid = work.wuser
JOIN
    institutions ON institutions.iid = work.winstitution
WHERE 
    uid = ?");
    $stmt_experience->bind_param("i", $id);
    if (!$stmt_experience->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_experience = $stmt_experience->get_result();
    $experiences = $result_experience->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_experience->close();

    $stmt_skills = $conn->prepare("SELECT 
    skills.skillname
FROM 
    users
JOIN
    acquired_skills ON users.uid = acquired_skills.auser
JOIN
    skills ON skills.skillid = acquired_skills.askill
WHERE 
    uid = ?");
    $stmt_skills->bind_param("i", $id);
    if (!$stmt_skills->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_skills = $stmt_skills->get_result();
    $skills = $result_skills->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_skills->close();

    $stmt_languages = $conn->prepare("SELECT 
    languages.lname
FROM 
    users
JOIN
    acquired_languages ON users.uid = acquired_languages.userid
JOIN
    languages ON languages.lid = acquired_languages.languageid
WHERE 
    uid = ?");
    $stmt_languages->bind_param("i", $id);
    if (!$stmt_languages->execute()) {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        exit;
    }
    $result_languages = $stmt_languages->get_result();
    $languages = $result_languages->fetch_all(MYSQLI_ASSOC); // Fetch as associative array
    $stmt_languages->close();
    

    $response = [
        'posts' => $posts,
        'user' => $users,
        'education' => $educations,
        'experience' => $experiences,
        'skills' => $skills,
        'languages' => $languages
    ];

    // Set header for JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

}
?>
