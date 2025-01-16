<?php

require_once "Database.php";

class Admin extends User
{
    public function __construct($nom = null, $prenom = null, $email = null, $mot_de_passe = null)
    {
        parent::__construct($nom, $prenom, $email, $mot_de_passe, 'admin');
    }

    public function validerCompteEnseignant($id_enseignant)
    {
        try {
            $conn = DatabaseConnection::getInstance()->getConnection();
            $query = "UPDATE users SET statut = 'active' WHERE id = :id AND role = 'teacher'";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id_enseignant, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de la validation du compte enseignant : " . $e->getMessage());
            return false;
        }
    }


    public function gererUtilisateur($id_user, $action, $data = null)
    {
        try {
            $conn = DatabaseConnection::getInstance()->getConnection();
            
            if ($action === 'delete') {
                $query = "DELETE FROM users WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":id", $id_user, PDO::PARAM_INT);
            } elseif ($action === 'update' && is_array($data)) {
                $fields = [];
                foreach ($data as $key => $value) {
                    $fields[] = "$key = :$key";
                }
                $query = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
                $stmt = $conn->prepare($query);
                foreach ($data as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
                $stmt->bindParam(":id", $id_user, PDO::PARAM_INT);
            } else {
                throw new Exception("Action invalide ou données insuffisantes.");
            }

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de la gestion de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function gererTags($action, $data)
    {
        try {
            $conn = DatabaseConnection::getInstance()->getConnection();
            
            if ($action === 'add') {
                $query = "INSERT INTO tags (name) VALUES (:name)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
            } elseif ($action === 'update' && isset($data['id'])) {
                $query = "UPDATE tags SET name = :name WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
                $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
            } elseif ($action === 'delete' && isset($data['id'])) {
                $query = "DELETE FROM tags WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
            } else {
                throw new Exception("Action invalide ou données insuffisantes.");
            }

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de la gestion des tags : " . $e->getMessage());
            return false;
        }
    }

    public function accepterCour($id_cour)
    {
        try {
            $conn = DatabaseConnection::getInstance()->getConnection();
            $query = "UPDATE courses SET statut = 'accepté' WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id_cour, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de l'acceptation du cours : " . $e->getMessage());
            return false;
        }
    }

 
    public function refuserCour($id_cour)
    {
        try {
            $conn = DatabaseConnection::getInstance()->getConnection();
            $query = "UPDATE courses SET statut = 'refusé' WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id_cour, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors du refus du cours : " . $e->getMessage());
            return false;
        }
    }
}


