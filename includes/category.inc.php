<?php
session_start();
require_once "../classes/Admin.php";

$admin = new Admin();

// Ajout d'une catégorie
if (isset($_POST["category-name"]) && isset($_POST["category-description"])) {
    $nom = htmlspecialchars($_POST["category-name"]);
    $description = htmlspecialchars($_POST["category-description"]);

    try {
        if ($admin->addCategory($nom, $description)) {
            $_SESSION["success"] = "Catégorie ajoutée avec succès!";
        } else {
            $_SESSION["error"] = "Erreur lors de l'ajout de la catégorie.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["error"] = "Une catégorie avec ce nom existe déjà.";
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de l'ajout de la catégorie.";
        }
    }

    header("Location: ../views/admin/dashboard.php");
    exit();
}

// Mise à jour d'une catégorie
if (isset($_POST["edit-category"])) {
    $id = intval($_POST["category-id"]);
    $nom = htmlspecialchars($_POST["category-name"]);
    $description = htmlspecialchars($_POST["category-description"]);

    try {
        if ($admin->updateCategory($id, $nom, $description)) {
            $_SESSION["success"] = "Catégorie mise à jour avec succès!";
        } else {
            $_SESSION["error"] = "Erreur lors de la mise à jour de la catégorie.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["error"] = "Une catégorie avec ce nom existe déjà.";
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de la mise à jour de la catégorie.";
        }
    }

    header("Location: ../views/admin/dashboard.php");
    exit();
}

// Suppression d'une catégorie
if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    try {
        if ($admin->deleteCategory($id)) {
            $_SESSION["success"] = "Catégorie supprimée avec succès!";
        } else {
            $_SESSION["error"] = "Erreur lors de la suppression de la catégorie.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION["error"] = "Impossible de supprimer cette catégorie car elle contient des cours.";
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de la suppression de la catégorie.";
        }
    }

    header("Location: ../views/admin/dashboard.php");
    exit();
}
?>
