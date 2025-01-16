<?php

require_once "Database.php";

 class  Visiteur extends User
{

    public function register($userData)
    {
        try {
            if (empty($userData['email']) || empty($userData['mot_de_passe']) || empty($userData['role'])) {
                throw new Exception("L'email, le mot de passe et le rôle sont requis.");
            }

            $userData['mot_de_passe'] = self::hashPassword($userData['mot_de_passe']);
            $conn = DatabaseConnection::getInstance()->getConnection();
            $query = "INSERT INTO users (name, lastname, email, password, role";
            $values = "VALUES (:nom, :prenom, :email, :mot_de_passe, :role";
            if ($userData['role'] === 'student') {
                $query .= ", status";
                $values .= ", :status";
            }

            $query .= ") " . $values . ")";
            $stmt = $conn->prepare($query);
            $stmt->execute($userData);

            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de l'inscription : " . $e->getMessage());
            return false;
        }
    }
}
