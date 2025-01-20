<?php
session_start();
include_once "../classes/Course.php";

$course = new Course("", "", "", "", "");
$courses = $course->getAllCourses();

echo json_encode($courses);
?>
