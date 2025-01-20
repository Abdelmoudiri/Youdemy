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

    // Validation methods
    private function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide");
        }
        return true;
    }

    private function validatePassword($password) {
        if (strlen($password) < 8) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères");
        }
        return true;
    }

    private function validatePhone($phone) {
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            throw new Exception("Le numéro de téléphone n'est pas valide");
        }
        return true;
    }

    // SETTERS
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    public function setTelephone($telephone) {
        $this->validatePhone($telephone);
        $this->telephone = $telephone;
    }
    public function setEmail($email) {
        $this->validateEmail($email);
        $this->email = $email;
    }
    public function setPassword($password) {
        $this->validatePassword($password);
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
            $this->validateEmail($email);
            
            $query = "SELECT u.*, r.label as role 
                     FROM users u 
                     JOIN roles r ON u.id_role = r.id_role 
                     WHERE u.email = :email";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            error_log("Login attempt for email: " . $email);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                error_log("Found user with password hash: " . $user['password']);
                error_log("Attempting to verify password");
                
                if (password_verify($password, $user['password'])) {
                    error_log("Password verified successfully");
                    return [
                        'success' => true,
                        'user' => [
                            'id' => $user['id_user'],
                            'nom' => $user['nom'],
                            'prenom' => $user['prenom'],
                            'email' => $user['email'],
                            'role' => $user['role'],
                            'photo' => $user['photo']
                        ]
                    ];
                } else {
                    error_log("Password verification failed");
                }
            } else {
                error_log("No user found with this email");
            }
            
            return [
                'success' => false,
                'message' => 'Email ou mot de passe incorrect'
            ];
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // REGISTER METHOD
    public function register($nom, $prenom, $telephone, $email, $password, $role) {
        try {
            error_log("Starting registration for email: " . $email);
            
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->database->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("Email already exists");
                echo '<script>alert("Email déjà utilisé !")</script>';
                return false;
            }

            $query = "INSERT INTO users (nom, prenom, phone, email, password, id_role, statut) VALUES (:nom, :prenom, :phone, :email, :password, :role, 'Actif')";
            $stmt = $this->database->prepare($query);
            
            // Récupérer l'ID du rôle
            $roleId = 2; // Par défaut, rôle étudiant
            if ($role === 'Enseignant') {
                $roleId = 3;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            error_log("Password hash created: " . $hashedPassword);
            
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':phone', $telephone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $roleId);
            
            if ($stmt->execute()) {
                error_log("User registered successfully");
                return true;
            }
            error_log("Registration failed");
            return false;
        } catch(PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
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