<?php
session_start();
include_once "../classes/Teacher.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['teacher-name'];
    $email = $_POST['teacher-email'];
    $password = $_POST['teacher-password'];

    $teacher = new Teacher($name, $email, $password);

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if ($teacher->addTeacher()) {
                    $_SESSION['success'] = "Teacher added successfully";
                } else {
                    $_SESSION['error'] = "Error adding teacher";
                }
                break;
            case 'edit':
                $id = $_POST['teacher-id'];
                if ($teacher->updateTeacher($id)) {
                    $_SESSION['success'] = "Teacher updated successfully";
                } else {
                    $_SESSION['error'] = "Error updating teacher";
                }
                break;
            case 'delete':
                $id = $_POST['teacher-id'];
                if ($teacher->deleteTeacher($id)) {
                    $_SESSION['success'] = "Teacher deleted successfully";
                } else {
                    $_SESSION['error'] = "Error deleting teacher";
                }
                break;
        }
    }
}

header("Location: ../views/admin/dashboard.php");
exit();
?>
