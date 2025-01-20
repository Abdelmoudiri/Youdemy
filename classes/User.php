<?php
require_once __DIR__ . '/../config/db.php';

class User {
    private $id;
    private $prenom;
    private $nom;
    private $telephone;
    private $email;
    private $password;
    private $role;
    private $photo;
    private $status;
    private $database;

    public function __construct($nom = '', $prenom = '', $telephone = '', $email = '', $password = '', $role = '', $status = '', $photo = 'user.png') {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
        $this->photo = $photo;
        $this->database = Database::getInstance()->getConnection();
    }

    // GETTERS
    public function getId() {
        return $this->id;
    }
    public function getNom() {
        return $this->nom;
    }
    public function getPrenom() {
        return $this->prenom;
    }
    public function getTelephone() {
        return $this->telephone;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getRole() {
        return $this->role;
    }
    public function getPhoto() {
        return $this->photo;
    }
    public function getStatus() {
        return $this->status;
    }

    // SETTERS
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setRole($role) {
        $this->role = $role;
    }
    public function setPhoto($photo) {
        $this->photo = $photo;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    protected function connect() {
        return $this->database;
    }

    // LOGIN METHOD
    public function login($email, $password) {
        try {
            $query = "SELECT u.*, r.label as role 
                     FROM users u 
                     JOIN roles r ON u.id_role = r.id_role 
                     WHERE u.email = :email";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    $this->id = $user['id_user'];
                    $this->prenom = $user['prenom'];
                    $this->nom = $user['nom'];
                    $this->email = $user['email'];
                    $this->telephone = $user['phone'];
                    $this->role = $user['role'];
                    $this->photo = $user['photo'];
                    $this->status = $user['statut'];
                    return $this;
                } else {
                    echo '<script>alert("Le mot de passe est incorrect !")</script>';
                    return false;
                }
            } else {
                echo '<script>alert("Aucun utilisateur trouvé avec cet email !")</script>';
                return false;
            }
        } catch(PDOException $e) {
            echo '<script>alert("Erreur lors de la connexion : ' . $e->getMessage() . '")</script>';
            return false;
        }
    }

    // REGISTER METHOD
    public function register() {
        try {
            // Vérifier si l'email existe déjà
            $query = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                echo '<script>alert("Cet email est déjà utilisé")</script>';
                return false;
            }

            // Insérer le nouvel utilisateur
            $query = "INSERT INTO users (prenom, nom, phone, email, password, photo, statut, id_role) 
                     VALUES (:prenom, :nom, :phone, :email, :password, :photo, :statut, :role)";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':prenom', $this->prenom, PDO::PARAM_STR);
            $stmt->bindValue(':nom', $this->nom, PDO::PARAM_STR);
            $stmt->bindValue(':phone', $this->telephone, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($this->password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':photo', $this->photo, PDO::PARAM_STR);
            $stmt->bindValue(':role', $this->role, PDO::PARAM_INT);
            
            if($this->role == 2) {
                $stmt->bindValue(':statut', 'Actif', PDO::PARAM_STR);
            } else {
                $stmt->bindValue(':statut', 'En Attente', PDO::PARAM_STR);
            }
            
            return $stmt->execute();
        } catch(PDOException $e) {
            echo '<script>alert("Erreur lors de l\'inscription : ' . $e->getMessage() . '")</script>';
            return false;
        }
    }

    // UPDATE USER PROFILE
    public function updateProfile($id, $prenom, $nom, $phone, $photo = null) {
        try {
            $query = "UPDATE users 
                     SET prenom = :prenom, 
                         nom = :nom, 
                         phone = :phone" . 
                     ($photo ? ", photo = :photo" : "") . 
                     " WHERE id_user = :id";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            if ($photo) {
                $stmt->bindValue(':photo', $photo, PDO::PARAM_STR);
            }
            
            return $stmt->execute();
        } catch(PDOException $e) {
            echo '<script>alert("Erreur lors de la mise à jour du profil : ' . $e->getMessage() . '")</script>';
            return false;
        }
    }

    // UPDATE PASSWORD
    public function updatePassword($id, $currentPassword, $newPassword) {
        try {
            // Vérifier l'ancien mot de passe
            $query = "SELECT password FROM users WHERE id_user = :id";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!password_verify($currentPassword, $user['password'])) {
                echo '<script>alert("Le mot de passe actuel est incorrect")</script>';
                return false;
            }

            // Mettre à jour le mot de passe
            $query = "UPDATE users SET password = :password WHERE id_user = :id";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':password', password_hash($newPassword, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            echo '<script>alert("Erreur lors de la mise à jour du mot de passe : ' . $e->getMessage() . '")</script>';
            return false;
        }
    }
}