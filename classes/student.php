<?php

require_once __DIR__ . '/../config/db.php';

class Student {
    private $id_user;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $photo;
    private $role;
    private $date_inscription;
    protected $database;

    public function __construct($nom = '', $prenom = '', $email = '', $password = '', $photo = '', $role = '', $date_inscription = '', $id_user = null) {
        $this->database = Database::getInstance()->getConnection();
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->photo = $photo;
        $this->role = $role;
        $this->date_inscription = $date_inscription;
        $this->id_user = $id_user;
    }

    public function getCourseDetails($courseId) {
        try {
            $sql = "SELECT c.*, u.nom, u.prenom, u.photo, cat.nom_categorie 
                    FROM courses c 
                    JOIN users u ON c.id_teacher = u.id_user 
                    LEFT JOIN categories cat ON c.id_categorie = cat.id_categorie 
                    WHERE c.id_course = :courseId";
            $stmt = $this->database->prepare($sql);
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                error_log("Course found: " . print_r($result, true));
            } else {
                error_log("No course found for ID: " . $courseId);
            }
            
            return $result;
        } catch(PDOException $e) {
            error_log("Erreur lors de la récupération des détails du cours : " . $e->getMessage());
            return null;
        }
    }

    public function isEnrolled($studentId, $courseId) {
        try {
            $sql = "SELECT COUNT(*) FROM enrollments WHERE id_student = :studentId AND id_course = :courseId";
            $stmt = $this->database->prepare($sql);
            $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch(PDOException $e) {
            error_log("Erreur lors de la vérification de l'inscription : " . $e->getMessage());
            return false;
        }
    }

    public function enrollCourse($courseId) {
        try {
            if ($this->isEnrolled($this->id_user, $courseId)) {
                return false;
            }

            $sql = "INSERT INTO enrollments (id_student, id_course, date_enrolement) VALUES (:studentId, :courseId, NOW())";
            $stmt = $this->database->prepare($sql);
            $stmt->bindParam(':studentId', $this->id_user, PDO::PARAM_INT);
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur lors de l'inscription au cours : " . $e->getMessage());
            return false;
        }
    }

    public function subscribeToCourse($student_id, $course_id) {
        try {
            // Vérifier si l'étudiant n'est pas déjà inscrit
            if ($this->isEnrolled($student_id, $course_id)) {
                return false;
            }

            $sql = "INSERT INTO enrollments (id_student, id_course, date_enrolement) 
                    VALUES (:student_id, :course_id, NOW())";
            $stmt = $this->database->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                error_log("Inscription réussie au cours ID: " . $course_id . " pour l'étudiant ID: " . $student_id);
                return true;
            } else {
                error_log("Échec de l'inscription au cours. Erreur SQL: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
        } catch(PDOException $e) {
            error_log("Erreur lors de l'inscription au cours : " . $e->getMessage());
            return false;
        }
    }

    public function subscribedCourses($studentId) {
        try {
            $sql = "SELECT c.*, u.nom, u.prenom, u.photo, cat.nom_categorie 
                    FROM courses c 
                    JOIN enrollments e ON c.id_course = e.id_course 
                    JOIN users u ON c.id_teacher = u.id_user 
                    LEFT JOIN categories cat ON c.id_categorie = cat.id_categorie 
                    WHERE e.id_student = :studentId AND c.statut_cours = 'Approuvé'";
            $stmt = $this->database->prepare($sql);
            $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur lors de la récupération des cours inscrits : " . $e->getMessage());
            return [];
        }
    }

    // Getters
    public function getId() {
        return $this->id_user;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getRole() {
        return $this->role;
    }

    public function getDateInscription() {
        return $this->date_inscription;
    }

    // Setters
    public function setId($id) {
        $this->id_user = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setDateInscription($date) {
        $this->date_inscription = $date;
    }
}