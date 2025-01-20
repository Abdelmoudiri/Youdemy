<?php
session_start();
require_once "../classes/Admin.php";

$admin = new Admin();

if (isset($_GET["action"]) && isset($_GET["id"])) {
    $action = $_GET["action"];
    $id = intval($_GET["id"]);

    try {
        switch($action) {
            case "approve":
                if ($admin->approveCourse($id)) {
                    $_SESSION["success"] = "Cours approuvé avec succès!";
                } else {
                    $_SESSION["error"] = "Erreur lors de l'approbation du cours.";
                }
                break;

            case "refuse":
                if ($admin->refuseCourse($id)) {
                    $_SESSION["success"] = "Cours refusé avec succès!";
                } else {
                    $_SESSION["error"] = "Erreur lors du refus du cours.";
                }
                break;

            case "delete":
                if ($admin->deleteCourse($id)) {
                    $_SESSION["success"] = "Cours supprimé avec succès!";
                } else {
                    $_SESSION["error"] = "Erreur lors de la suppression du cours.";
                }
                break;

            default:
                $_SESSION["error"] = "Action non valide.";
                break;
        }
    } catch (PDOException $e) {
        $_SESSION["error"] = "Une erreur est survenue lors du traitement de votre demande.";
    }
    
    header("Location: ../views/admin/dashboard.php");
    exit();
}
?>
