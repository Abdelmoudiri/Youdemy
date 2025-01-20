<?php

require_once __DIR__ . '/../config/db.php';

class CourseManager {
    private $database;

    public function __construct() {
        $this->database = Database::getInstance()->getConnection();
    }

    public function getCourses($status, $limit, $offset) {
        try {
            $query = "SELECT c.*, u.prenom, u.nom, u.photo, cat.nom_categorie
                     FROM courses c
                     JOIN users u ON c.id_teacher = u.id_user
                     JOIN categories cat ON c.id_categorie = cat.id_categorie
                     WHERE c.statut_cours = :status
                     ORDER BY c.date_publication DESC
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des cours : " . $e->getMessage());
        }
    }

    public function getCoursesByCategory($categoryId, $status, $limit, $offset) {
        try {
            $query = "SELECT c.*, u.prenom, u.nom, u.photo, cat.nom_categorie
                     FROM courses c
                     JOIN users u ON c.id_teacher = u.id_user
                     JOIN categories cat ON c.id_categorie = cat.id_categorie
                     WHERE c.id_categorie = :categoryId AND c.statut_cours = :status
                     ORDER BY c.date_publication DESC
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des cours : " . $e->getMessage());
        }
    }

    public function getCourseById($courseId) {
        try {
            $query = "SELECT c.*, u.prenom, u.nom, u.photo, cat.nom_categorie
                     FROM courses c
                     JOIN users u ON c.id_teacher = u.id_user
                     JOIN categories cat ON c.id_categorie = cat.id_categorie
                     WHERE c.id_course = :courseId AND c.statut_cours = 'Approuvé'";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération du cours : " . $e->getMessage());
        }
    }

    public function countCourses($status) {
        try {
            $query = "SELECT COUNT(*) as total FROM courses WHERE statut_cours = :status";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Erreur lors du comptage des cours : " . $e->getMessage());
        }
    }

    public function countCoursesByCategory($categoryId, $status) {
        try {
            $query = "SELECT COUNT(*) as total FROM courses WHERE id_categorie = :categoryId AND statut_cours = :status";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Erreur lors du comptage des cours : " . $e->getMessage());
        }
    }
}
