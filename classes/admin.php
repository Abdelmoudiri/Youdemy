<?php

    require_once __DIR__ . '/User.php';
    require_once __DIR__ . '../../config/db.php';

    class Admin extends User {
        
        // Dashboard Statistics
        public function getNumCourses() {
            $sql = "SELECT COUNT(*) as num FROM courses";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        }

        public function getNumTeachers() {
            $sql = "SELECT COUNT(*) as num FROM users u 
                    JOIN roles r ON u.id_role = r.id_role 
                    WHERE r.label = 'Enseignant'";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        }

        public function getNumStudents() {
            $sql = "SELECT COUNT(*) as num FROM users u 
                    JOIN roles r ON u.id_role = r.id_role 
                    WHERE r.label = 'Etudiant'";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        }

        public function getNumCategories() {
            $sql = "SELECT COUNT(*) as num FROM categories";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        }

        public function getNumTags() {
            $sql = "SELECT COUNT(*) as num FROM tags";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        }

        // Course Management
        public function showCourses() {
            $sql = "SELECT c.*, u.prenom, u.nom, u.id_user, 
                    CASE 
                        WHEN c.statut_cours IS NULL THEN 'pending'
                        ELSE c.statut_cours 
                    END as status
                   FROM courses c 
                   JOIN users u ON c.id_teacher = u.id_user 
                   ORDER BY c.id_course DESC";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function getCoursesByStatus($status) {
            $sql = "SELECT COUNT(*) as num FROM courses WHERE statut_cours = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$status]);
            return $stmt->fetch();
        }

        public function approveCourse($courseId) {
            $sql = "UPDATE courses SET statut_cours = 'Approuvé' WHERE id_course = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$courseId]);
        }

        public function refuseCourse($courseId) {
            $sql = "UPDATE courses SET statut_cours = 'Refusé' WHERE id_course = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$courseId]);
        }

        public function deleteCourse($courseId) {
            $sql = "DELETE FROM courses WHERE id_course = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$courseId]);
        }

        // Category Management
        public function showCategories() {
            $sql = "SELECT c.*, (SELECT COUNT(*) FROM courses WHERE id_categorie = c.id_categorie) as num_courses 
                    FROM categories c 
                    ORDER BY c.id_categorie DESC";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function addCategory($nom, $description) {
            $sql = "INSERT INTO categories (nom_categorie, description) VALUES (?, ?)";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$nom, $description]);
        }

        public function updateCategory($id, $nom, $description) {
            $sql = "UPDATE categories SET nom_categorie = ?, description = ? WHERE id_categorie = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$nom, $description, $id]);
        }

        public function deleteCategory($id) {
            $sql = "DELETE FROM categories WHERE id_categorie = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$id]);
        }

        // Tag Management
        public function showTags() {
            $sql = "SELECT t.*, (SELECT COUNT(*) FROM courses_tags WHERE id_tag = t.id_tag) as num_courses 
                    FROM tags t 
                    ORDER BY t.id_tag DESC";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function addTag($nom) {
            $sql = "INSERT INTO tags (nom_tag) VALUES (?)";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$nom]);
        }

        public function updateTag($id, $nom) {
            $sql = "UPDATE tags SET nom_tag = ? WHERE id_tag = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$nom, $id]);
        }

        public function deleteTag($id) {
            $sql = "DELETE FROM tags WHERE id_tag = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$id]);
        }

        // User Management
        public function showTeachers() {
            $sql = "SELECT u.*, r.label as role_name, 
                    COALESCE(
                        (SELECT COUNT(*) 
                         FROM courses c 
                         WHERE c.id_teacher = u.id_user), 0
                    ) as course_count,
                    COALESCE(u.statut_compte, 'pending') as status
                   FROM users u 
                   JOIN roles r ON u.id_role = r.id_role
                   WHERE r.label = 'teacher'
                   ORDER BY u.id_user DESC";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function showStudents() {
            $sql = "SELECT u.*, r.label as role_name,
                    (SELECT COUNT(*) FROM enrollments WHERE id_student = u.id_user) as num_enrollments
                    FROM users u 
                    JOIN roles r ON u.id_role = r.id_role 
                    WHERE r.label = 'Etudiant'
                    ORDER BY u.id_user DESC";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function activateUser($userId) {
            $sql = "UPDATE users SET statut = 'Actif' WHERE id_user = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$userId]);
        }

        public function blockUser($userId) {
            $sql = "UPDATE users SET statut = 'Bloqué' WHERE id_user = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$userId]);
        }

        public function deleteUser($userId) {
            $sql = "DELETE FROM users WHERE id_user = ?";
            $stmt = $this->connect()->prepare($sql);
            return $stmt->execute([$userId]);
        }

        // Statistics and Reports
        public function getCoursesStats() {
            $sql = "SELECT 
                    COUNT(CASE WHEN statut_cours = 'Approuvé' THEN 1 END) as approved,
                    COUNT(CASE WHEN statut_cours = 'En Attente' THEN 1 END) as pending,
                    COUNT(CASE WHEN statut_cours = 'Refusé' THEN 1 END) as refused
                    FROM courses";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        }

        public function getUserStats() {
            $sql = "SELECT 
                    COUNT(CASE WHEN u.statut = 'Actif' THEN 1 END) as active,
                    COUNT(CASE WHEN u.statut = 'En Attente' THEN 1 END) as pending,
                    COUNT(CASE WHEN u.statut = 'Bloqué' THEN 1 END) as blocked,
                    r.label as role
                    FROM users u
                    JOIN roles r ON u.id_role = r.id_role
                    GROUP BY r.label";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function getMostPopularCategories($limit = 5) {
            $sql = "SELECT c.nom_categorie, COUNT(co.id_course) as num_courses
                    FROM categories c
                    LEFT JOIN courses co ON c.id_categorie = co.id_categorie
                    GROUP BY c.id_categorie, c.nom_categorie
                    ORDER BY num_courses DESC
                    LIMIT ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        }

        public function getMostActiveTeachers($limit = 5) {
            $sql = "SELECT u.prenom, u.nom, COUNT(c.id_course) as num_courses
                    FROM users u
                    JOIN roles r ON u.id_role = r.id_role
                    LEFT JOIN courses c ON u.id_user = c.id_teacher
                    WHERE r.label = 'Enseignant'
                    GROUP BY u.id_user, u.prenom, u.nom
                    ORDER BY num_courses DESC
                    LIMIT ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        }

        // Get total number of courses
        public function getTotalCourses() {
            try {
                $stmt = $this->connect()->query("SELECT COUNT(*) as total FROM courses");
                $result = $stmt->fetch();
                return $result['total'];
            } catch(PDOException $e) {
                return 0;
            }
        }

        // Get total number of categories
        public function getTotalCategories() {
            try {
                $stmt = $this->connect()->query("SELECT COUNT(*) as total FROM categories");
                $result = $stmt->fetch();
                return $result['total'];
            } catch(PDOException $e) {
                return 0;
            }
        }

        // Get recent activities
        public function getRecentActivities() {
            try {
                $activities = [];
                
                // Get recent courses
                $stmt = $this->connect()->query("SELECT 'course' as type, titre as title, 'Added new course' as description, created_at as date FROM courses ORDER BY created_at DESC LIMIT 3");
                while($row = $stmt->fetch()) {
                    $activities[] = [
                        'type' => $row['type'],
                        'title' => $row['title'],
                        'description' => $row['description'],
                        'time' => $this->timeAgo($row['date']),
                        'color' => 'blue',
                        'icon' => 'book'
                    ];
                }
                
                // Get recent students
                $stmt = $this->connect()->query("SELECT 'student' as type, CONCAT(nom, ' ', prenom) as title, 'New student registered' as description, created_at as date FROM users WHERE role = 'student' ORDER BY created_at DESC LIMIT 3");
                while($row = $stmt->fetch()) {
                    $activities[] = [
                        'type' => $row['type'],
                        'title' => $row['title'],
                        'description' => $row['description'],
                        'time' => $this->timeAgo($row['date']),
                        'color' => 'green',
                        'icon' => 'user'
                    ];
                }
                
                // Sort by date
                usort($activities, function($a, $b) {
                    return strtotime($b['time']) - strtotime($a['time']);
                });
                
                return array_slice($activities, 0, 5);
            } catch(PDOException $e) {
                return [];
            }
        }

        // Helper function to convert timestamp to "time ago" format
        private function timeAgo($timestamp) {
            $time = strtotime($timestamp);
            $current = time();
            $diff = $current - $time;
            
            $intervals = array(
                31536000 => 'year',
                2592000 => 'month',
                604800 => 'week',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
                1 => 'second'
            );
            
            foreach ($intervals as $secs => $str) {
                $d = $diff / $secs;
                if ($d >= 1) {
                    $r = round($d);
                    return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
                }
            }
            
            return 'just now';
        }
    }

?>