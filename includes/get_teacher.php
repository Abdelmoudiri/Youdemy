<?php
session_start();
include_once "../classes/Teacher.php";

if (isset($_GET['id'])) {
    $teacher = new Teacher("", "", "");
    $teacherData = $teacher->getTeacherById($_GET['id']);
    
    if ($teacherData) {
        echo json_encode($teacherData);
    } else {
        echo json_encode(['error' => 'Teacher not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>
