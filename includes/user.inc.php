<?php
session_start();
require_once "../classes/Admin.php";

$admin = new Admin();

if (isset($_GET["action"]) && isset($_GET["id"])) {
    $action = $_GET["action"];
    $id = intval($_GET["id"]);

    try {
        switch($action) {
            case "activate":
                if ($admin->activateUser($id)) {
                    $_SESSION["success"] = "Utilisateur activé avec succès!";
                } else {
                    $_SESSION["error"] = "Erreur lors de l'activation de l'utilisateur.";
                }
                break;

            case "block":
                if ($admin->blockUser($id)) {
                    $_SESSION["success"] = "Utilisateur bloqué avec succès!";
                } else {
                    $_SESSION["error"] = "Erreur lors du blocage de l'utilisateur.";
                }
                break;

            case "delete":
                if ($admin->deleteUser($id)) {
                    $_SESSION["success"] = "Utilisateur supprimé avec succès!";
                } else {
                    $_SESSION["error"] = "Erreur lors de la suppression de l'utilisateur.";
                }
                break;

            default:
                $_SESSION["error"] = "Action non valide.";
                break;
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["error"] = "Impossible de supprimer cet utilisateur car il a des cours ou des inscriptions associés.";
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors du traitement de votre demande.";
        }
    }
    
    header("Location: ../views/admin/dashboard.php");
    exit();
}
?>
