<?php

require_once "Database.php";

 class  Etudiant extends User
{
    public function __construct($nom = null, $prenom = null, $email = null, $mot_de_passe = null, $role = null)
    {
        parent::__construct($nom, $prenom, $email, $mot_de_passe, 'student');
    }

    public function inserer_cour($id_cour)
    {
        if (!is_numeric($id_cour) || $id_cour <= 0) {
            throw new InvalidArgumentException("L'ID du cours doit être un entier positif.");
        }

        try {
            $conn = DatabaseConnection::getInstance()->getConnection();

            $queryCheckCourse = "SELECT id FROM courses WHERE id = :id_cour";
            $stmtCheck = $conn->prepare($queryCheckCourse);
            $stmtCheck->bindParam(":id_cour", $id_cour, PDO::PARAM_INT);
            $stmtCheck->execute();
            if (!$stmtCheck->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("Le cours avec l'ID $id_cour n'existe pas.");
            }

            $queryInsert = "INSERT INTO student_courses (student_id, course_id) VALUES (:student_id, :course_id)";
            $stmtInsert = $conn->prepare($queryInsert);
            $stmtInsert->bindParam(":student_id", $this->getId(), PDO::PARAM_INT);
            $stmtInsert->bindParam(":course_id", $id_cour, PDO::PARAM_INT);
            return $stmtInsert->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de l'inscription au cours : " . $e->getMessage());
            return false;
        }
    }
}

