<?php

require_once "Database.php";

class User
{
    protected $nom;
    protected $prenom;
    protected $email;
    protected $mot_de_passe;
    protected $role;
    protected $statut;
    protected $date_creation;

    public function __construct() {}

    public function login($email, $password)
    {
        try {
            $conn = DatabaseConnection::getInstance()->getConnection();
            $query = "select * from utilisateurs where email=:email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && $user["password"] === $password) {
                return $user;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "error de login : " . $e->getMessage();
        }
    }
}
