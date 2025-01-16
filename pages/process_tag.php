<?php
session_start();
require_once "../classes/Tag.php";
require_once "../classes/AdminDash.php";

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $tagName = $_POST['name'] ?? '';
            try {
                $tag = new Tag($tagName);
                if ($tag->save()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Tag ajouté avec succès',
                        'tag' => [
                            'id' => $tag->getId(),
                            'name' => $tag->getName()
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de l\'ajout du tag'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'delete':
            $tagId = $_POST['id'] ?? '';
            try {
                $tag = Tag::findByName($tagId);
                if ($tag && $tag->delete()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Tag supprimé avec succès'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de la suppression du tag'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'bulk_insert':
            $tags = json_decode($_POST['tags'], true);
            try {
                $admin = new AdminDash();
                if ($admin->bulkInsertTags($tags)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Tags ajoutés avec succès'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de l\'ajout des tags'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => 'Action non reconnue'
            ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
