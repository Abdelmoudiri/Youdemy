<?php
session_start();

require_once '../../config/db.php';
require_once '../../classes/student.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $courseId = $_POST['course_id'];
    $etudiant = new Student('','','','','','','', $_SESSION['id_user']);
    
    if ($etudiant->enrollCourse($courseId)) {
        $_SESSION['success'] = "Vous êtes maintenant inscrit au cours !";
        header("Location: view_course.php?id=" . $courseId);
    } else {
        $_SESSION['error'] = "Une erreur s'est produite lors de l'inscription au cours.";
        header("Location: details.php?id=" . $courseId);
    }
    exit;
} else {
    header("Location: courses.php");
    exit;
}
