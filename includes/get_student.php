<?php
session_start();
include_once "../classes/Student.php";

if (isset($_GET['id'])) {
    $student = new Student("", "", "");
    $studentData = $student->getStudentById($_GET['id']);
    
    if ($studentData) {
        echo json_encode($studentData);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>
