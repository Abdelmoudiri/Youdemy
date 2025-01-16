<?php

require_once "Database.php";
require_once "User.php";
require_once "Course.php";
require_once "Tag.php";

class AdminDash {
    private $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    // Gestion des comptes enseignants
    public function validateTeacherAccount($teacherId) {
        $stmt = $this->db->prepare("UPDATE users SET statut = 'actif' WHERE id = :id AND role = 'enseignant'");
        return $stmt->execute(['id' => $teacherId]);
    }

    // Gestion des utilisateurs
    public function manageUserStatus($userId, $status) {
        if (!in_array($status, ['actif', 'inactif', 'suspendu'])) {
            throw new Exception("Statut invalide");
        }
        $stmt = $this->db->prepare("UPDATE users SET statut = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $userId]);
    }

    public function deleteUser($userId) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $userId]);
    }

    // Gestion des tags
    public function bulkInsertTags($tagsList) {
        return Tag::bulkInsert($tagsList);
    }

    // Statistiques globales
    public function getTotalCourseCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM courses");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getCourseCountByCategory() {
        $query = "SELECT c.nom as category, COUNT(co.id) as count 
                 FROM categories c 
                 LEFT JOIN courses co ON c.id = co.category_id 
                 GROUP BY c.id";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMostPopularCourse() {
        $query = "SELECT c.title, COUNT(e.id) as student_count 
                 FROM courses c 
                 LEFT JOIN enrollments e ON c.id = e.course_id 
                 GROUP BY c.id 
                 ORDER BY student_count DESC 
                 LIMIT 1";
        return $this->db->query($query)->fetch(PDO::FETCH_ASSOC);
    }

    public function getTopTeachers($limit = 3) {
        $query = "SELECT u.nom, u.prenom, COUNT(c.id) as course_count, 
                        COUNT(DISTINCT e.student_id) as total_students
                 FROM users u 
                 LEFT JOIN courses c ON u.id = c.teacher_id
                 LEFT JOIN enrollments e ON c.id = e.course_id
                 WHERE u.role = 'enseignant'
                 GROUP BY u.id
                 ORDER BY total_students DESC, course_count DESC
                 LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gestion des catégories
    public function addCategory($name, $description) {
        $stmt = $this->db->prepare("INSERT INTO categories (nom, description) VALUES (:nom, :description)");
        return $stmt->execute(['nom' => $name, 'description' => $description]);
    }

    public function updateCategory($id, $name, $description) {
        $stmt = $this->db->prepare("UPDATE categories SET nom = :nom, description = :description WHERE id = :id");
        return $stmt->execute(['id' => $id, 'nom' => $name, 'description' => $description]);
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Statistiques avancées
    public function getCourseEngagementRate() {
        $query = "SELECT c.title,
                        COUNT(DISTINCT e.student_id) as enrolled_students,
                        COUNT(DISTINCT com.user_id) as active_students,
                        ROUND(COUNT(DISTINCT com.user_id) * 100.0 / NULLIF(COUNT(DISTINCT e.student_id), 0), 2) as engagement_rate
                 FROM courses c
                 LEFT JOIN enrollments e ON c.id = e.course_id
                 LEFT JOIN comments com ON c.id = com.course_id
                 GROUP BY c.id
                 ORDER BY engagement_rate DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopularCategories() {
        $query = "SELECT cat.nom as category,
                        COUNT(DISTINCT e.student_id) as total_students,
                        COUNT(DISTINCT c.id) as course_count
                 FROM categories cat
                 LEFT JOIN courses c ON cat.id = c.category_id
                 LEFT JOIN enrollments e ON c.id = e.course_id
                 GROUP BY cat.id
                 ORDER BY total_students DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}
