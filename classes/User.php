<?php

require_once "Database.php";

class User
{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $mot_de_passe;
    private $role;
    private $statut;
    private $date_creation;
    const ROLE_STUDENT = 'student';
    const ROLE_TEACHER = 'teacher';
    const ROLE_ADMIN = 'admin';

    public function __construct($nom = null, $prenom = null, $email = null, $mot_de_passe = null, $role = null)
        {
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->mot_de_passe = $mot_de_passe;
            $this->role = $role;
        }
    public function login($email, $password)
        {
            try {
                if (empty($email) || empty($password)) {
                    throw new Exception("L'email et le mot de passe sont requis.");
                }
                $conn = DatabaseConnection::getInstance()->getConnection();
                $query = "SELECT * FROM users WHERE email = :email";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user && password_verify($password, $user['password'])) {
                    return $user;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                error_log("Erreur de connexion : " . $e->getMessage());
                return false;
            }
        }

    public static function hashPassword($password)
        {
            return password_hash($password, PASSWORD_BCRYPT);
        }

    public function register($userData)
        {
            try {
                // Validation des données obligatoires
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
