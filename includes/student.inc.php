<?php
session_start();
include_once "../classes/Student.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['student-name'];
    $email = $_POST['student-email'];
    $password = $_POST['student-password'];

    $student = new Student($name, $email, $password);

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if ($student->addStudent()) {
                    $_SESSION['success'] = "Student added successfully";
                } else {
                    $_SESSION['error'] = "Error adding student";
                }
                break;
            case 'edit':
                $id = $_POST['student-id'];
                if ($student->updateStudent($id)) {
                    $_SESSION['success'] = "Student updated successfully";
                } else {
                    $_SESSION['error'] = "Error updating student";
                }
                break;
            case 'delete':
                $id = $_POST['student-id'];
                if ($student->deleteStudent($id)) {
                    $_SESSION['success'] = "Student deleted successfully";
                } else {
                    $_SESSION['error'] = "Error deleting student";
                }
                break;
            case 'enroll':
                $courseId = $_POST['course-id'];
                if ($student->enrollCourse($courseId)) {
                    $_SESSION['success'] = "Student enrolled successfully";
                } else {
                    $_SESSION['error'] = "Error enrolling student";
                }
                break;
        }
    }
}

header("Location: ../views/admin/dashboard.php");
exit();
?>
