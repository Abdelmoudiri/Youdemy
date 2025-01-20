<?php

require_once __DIR__ . '/../config/db.php';

class Categorie {
    private $database;
    private $id;
    private $nom;
    private $description;

    public function __construct($nom = '', $description = '') {
        $this->database = Database::getInstance()->getConnection();
        $this->nom = $nom;
        $this->description = $description;
    }

    public function getName() {
        return $this->nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setName($nom) {
        $this->nom = $nom;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function loadById($id) {
        try {
            $query = "SELECT id_categorie, nom_categorie, description FROM categories WHERE id_categorie = ?";
            $stmt = $this->database->prepare($query);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                $this->id = $data['id_categorie'];
                $this->nom = $data['nom_categorie'];
                $this->description = $data['description'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors du chargement de la catégorie : " . $e->getMessage());
        }
    }

    public function getCoursesPerCategory($status) {
        try {
            $query = "SELECT 
                        c.id_categorie,
                        c.nom_categorie as categorie,
                        c.description,
                        COUNT(CASE WHEN co.statut = ? THEN 1 END) as total_approved_courses
                    FROM categories c
                    LEFT JOIN courses co ON c.id_categorie = co.id_categorie
                    GROUP BY c.id_categorie, c.nom_categorie, c.description
                    ORDER BY c.nom_categorie";
            
            $stmt = $this->database->prepare($query);
            $stmt->execute([$status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur dans getCoursesPerCategory: " . $e->getMessage());
            return false;
        }
    }

    public function createCategory($nom, $description) {
        try {
            $query = "INSERT INTO categories (nom_categorie, description) VALUES (?, ?)";
            $stmt = $this->database->prepare($query);
            return $stmt->execute([$nom, $description]);
        } catch (PDOException $e) {
            die("Erreur lors de la création de la catégorie : " . $e->getMessage());
        }
    }

    public function updateCategory($id, $nom, $description) {
        try {
            $query = "UPDATE categories SET nom_categorie = ?, description = ? WHERE id_categorie = ?";
            $stmt = $this->database->prepare($query);
            return $stmt->execute([$nom, $description, $id]);
        } catch (PDOException $e) {
            die("Erreur lors de la modification de la catégorie : " . $e->getMessage());
        }
    }

    public function deleteCategory($id) {
        try {
            $query = "DELETE FROM categories WHERE id_categorie = ?";
            $stmt = $this->database->prepare($query);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            die("Erreur lors de la suppression de la catégorie : " . $e->getMessage());
        }
    }

    public function getAllCategories() {
        try {
            $sql = "SELECT id_categorie, nom_categorie, description FROM categories ORDER BY nom_categorie";
            $stmt = $this->database->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur lors de la récupération des catégories : " . $e->getMessage());
            return [];
        }
    }

    public function addCourseCategory($courseId, $categoryId) {
        try {
            $query = "INSERT INTO courses_categories (id_course, id_categorie) VALUES (?, ?)";
            $stmt = $this->database->prepare($query);
            return $stmt->execute([$courseId, $categoryId]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la catégorie au cours : " . $e->getMessage());
            return false;
        }
    }
}
