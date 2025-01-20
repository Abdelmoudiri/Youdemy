<?php
session_start();
require_once "../classes/Admin.php";

$admin = new Admin();

// Ajout d'un tag
if (isset($_POST["tag-name"])) {
    $nom = htmlspecialchars($_POST["tag-name"]);

    try {
        if ($admin->addTag($nom)) {
            $_SESSION["success"] = "Tag ajouté avec succès!";
        } else {
            $_SESSION["error"] = "Erreur lors de l'ajout du tag.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["error"] = "Un tag avec ce nom existe déjà.";
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de l'ajout du tag.";
        }
    }

    header("Location: ../views/admin/dashboard.php");
    exit();
}

// Mise à jour d'un tag
if (isset($_POST["edit-tag"])) {
    $id = intval($_POST["tag-id"]);
    $nom = htmlspecialchars($_POST["tag-name"]);

    try {
        if ($admin->updateTag($id, $nom)) {
            $_SESSION["success"] = "Tag mis à jour avec succès!";
        } else {
            $_SESSION["error"] = "Erreur lors de la mise à jour du tag.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["error"] = "Un tag avec ce nom existe déjà.";
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de la mise à jour du tag.";
        }
    }

    header("Location: ../views/admin/dashboard.php");
    exit();
}

// Suppression d'un tag
if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    try {
        if ($admin->deleteTag($id)) {
            $_SESSION["success"] = "Tag supprimé avec succès!";
        } else {
            $_SESSION["error"] = "Erreur lors de la suppression du tag.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["error"] = "Impossible de supprimer ce tag car il est utilisé par des cours.";
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de la suppression du tag.";
        }
    }

    header("Location: ../views/admin/dashboard.php");
    exit();
}
?>
