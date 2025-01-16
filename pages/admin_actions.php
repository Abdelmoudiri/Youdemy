<?php
session_start();
require_once '../config.php';
require_once '../includes/functions.php';
require_once '../classes/DatabaseConnection.php';
require_once '../classes/Course.php';
require_once '../classes/User.php';
require_once '../classes/Category.php';
require_once '../classes/Tag.php';

// Vérification des droits d'admin
// checkAdmin();

// Vérification de la requête AJAX


$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        // Actions des cours
        case 'list_courses':
            $courses = Course::getAll();
            if ($courses === false) {
                throw new Exception("Erreur lors de la récupération des cours");
            }
            jsonResponse(true, ['courses' => $courses]);
            break;

        case 'pending_courses':
            $courses = Course::getPending();
            if ($courses === false) {
                throw new Exception("Erreur lors de la récupération des cours en attente");
            }
            jsonResponse(true, ['courses' => $courses]);
            break;

        case 'validate_course':
            $courseId = $_POST['id'] ?? 0;
            if (!$courseId) {
                throw new Exception("ID du cours manquant");
            }
            
            $course = Course::getById($courseId);
            if (!$course) {
                throw new Exception("Cours non trouvé");
            }
            
            if (!$course->validate()) {
                throw new Exception("Erreur lors de la validation du cours");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        case 'reject_course':
            $courseId = $_POST['id'] ?? 0;
            if (!$courseId) {
                throw new Exception("ID du cours manquant");
            }
            
            $course = Course::getById($courseId);
            if (!$course) {
                throw new Exception("Cours non trouvé");
            }
            
            if (!$course->reject()) {
                throw new Exception("Erreur lors du rejet du cours");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        // Actions des utilisateurs
        case 'list_users':
            $users = User::getAll();
            if ($users === false) {
                throw new Exception("Erreur lors de la récupération des utilisateurs");
            }
            jsonResponse(true, ['users' => $users]);
            break;

        case 'edit_user':
            $userId = $_POST['id'] ?? 0;
            if (!$userId) {
                throw new Exception("ID de l'utilisateur manquant");
            }
            
            $user = User::getById($userId);
            if (!$user) {
                throw new Exception("Utilisateur non trouvé");
            }

            $data = [
                'nom' => $_POST['name'] ?? '',
                'prenom' => $_POST['firstname'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role' => $_POST['role'] ?? ''
            ];

            if (empty($data['email'])) {
                throw new Exception("L'email est requis");
            }

            if (!$user->update($data)) {
                throw new Exception("Erreur lors de la mise à jour de l'utilisateur");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        case 'delete_user':
            $userId = $_POST['id'] ?? 0;
            if (!$userId) {
                throw new Exception("ID de l'utilisateur manquant");
            }
            
            $user = User::getById($userId);
            if (!$user) {
                throw new Exception("Utilisateur non trouvé");
            }
            
            if (!$user->delete()) {
                throw new Exception("Erreur lors de la suppression de l'utilisateur");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        // Actions des catégories
        case 'list_categories':
            $categories = Category::getAll();
            if ($categories === false) {
                throw new Exception("Erreur lors de la récupération des catégories");
            }
            jsonResponse(true, ['categories' => $categories]);
            break;

        case 'add_category':
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name)) {
                throw new Exception("Le nom de la catégorie est requis");
            }

            $category = new Category($name, $description);
            if (!$category->create(['name' => $name, 'description' => $description])) {
                throw new Exception("Erreur lors de la création de la catégorie");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        case 'edit_category':
            $categoryId = $_POST['id'] ?? 0;
            if (!$categoryId) {
                throw new Exception("ID de la catégorie manquant");
            }
            
            $category = Category::getById($categoryId);
            if (!$category) {
                throw new Exception("Catégorie non trouvée");
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];

            if (empty($data['name'])) {
                throw new Exception("Le nom de la catégorie est requis");
            }

            if (!$category->update($data)) {
                throw new Exception("Erreur lors de la mise à jour de la catégorie");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        case 'delete_category':
            $categoryId = $_POST['id'] ?? 0;
            if (!$categoryId) {
                throw new Exception("ID de la catégorie manquant");
            }
            
            $category = Category::getById($categoryId);
            if (!$category) {
                throw new Exception("Catégorie non trouvée");
            }
            
            if (!$category->delete()) {
                throw new Exception("Erreur lors de la suppression de la catégorie");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        // Actions des tags
        case 'list_tags':
            $tags = Tag::getAll();
            if ($tags === false) {
                throw new Exception("Erreur lors de la récupération des tags");
            }
            jsonResponse(true, ['tags' => $tags]);
            break;

        case 'add_tag':
            $name = $_POST['name'] ?? '';
            if (empty($name)) {
                throw new Exception("Le nom du tag est requis");
            }
            
            $tag = new Tag($name);
            if (!$tag->save()) {
                throw new Exception("Erreur lors de la création du tag");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        case 'delete_tag':
            $tagId = $_POST['id'] ?? 0;
            if (!$tagId) {
                throw new Exception("ID du tag manquant");
            }
            
            $tag = Tag::getById($tagId);
            if (!$tag) {
                throw new Exception("Tag non trouvé");
            }
            
            if (!$tag->delete()) {
                throw new Exception("Erreur lors de la suppression du tag");
            }
            
            jsonResponse(true, null, MSG_SUCCESS);
            break;

        default:
            throw new Exception('Action non reconnue');
    }
} catch (Exception $e) {
    error_log("Erreur dans admin_actions.php : " . $e->getMessage());
    jsonResponse(false, null, $e->getMessage());
}
