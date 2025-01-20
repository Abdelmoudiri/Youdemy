<?php
require_once __DIR__ . '/course.php';

class DocumentCourse extends Course {
    private string $contenu;
    private string $format_document;
    private int $taille;
    protected $database;

    public function __construct(
        string $titre = '',
        string $description = '',
        string $couverture = '',
        string $contenu = '',
        string $format_document = 'pdf',
        int $taille = 0,
        string $statut = '',
        string $niveau = ''
    ) {
        parent::__construct($titre, $description, $couverture, $statut, $niveau);
        $this->contenu = $contenu;
        $this->format_document = $format_document;
        $this->taille = $taille;
        $this->database = Database::getInstance()->getConnection();
    }

    public function afficherDetails() {
        return [
            'type' => 'document',
            'document_info' => [
                'contenu' => $this->contenu,
                'format' => $this->format_document,
                'taille' => $this->taille
            ],
            'details_cours' => [
                'titre' => $this->titre,
                'description' => $this->description,
                'niveau' => $this->niveau,
                'couverture' => $this->couverture,
                'status' => $this->status,
                'date' => $this->date
            ]
        ];
    }

    public function getCourseTags($course_id) {
        try {
            $query = "SELECT t.* 
                     FROM tags t 
                     INNER JOIN cours_tags ct ON t.id_tag = ct.id_tag 
                     WHERE ct.id_cours = :course_id";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur lors de la récupération des tags du cours : " . $e->getMessage());
        }
    }

    public function create($id_teacher) {
        try {
            $query = "INSERT INTO courses (titre, description, couverture, contenu, format_document, taille, statut_cours, niveau, id_teacher, date_creation) 
                     VALUES (:titre, :description, :couverture, :contenu, :format_document, :taille, :statut, :niveau, :id_teacher, NOW())";
            
            $stmt = $this->database->prepare($query);
            
            $stmt->bindValue(':titre', $this->titre, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindValue(':couverture', $this->couverture, PDO::PARAM_STR);
            $stmt->bindValue(':contenu', $this->contenu, PDO::PARAM_STR);
            $stmt->bindValue(':format_document', $this->format_document, PDO::PARAM_STR);
            $stmt->bindValue(':taille', $this->taille, PDO::PARAM_INT);
            $stmt->bindValue(':statut', $this->status, PDO::PARAM_STR);
            $stmt->bindValue(':niveau', $this->niveau, PDO::PARAM_STR);
            $stmt->bindValue(':id_teacher', $id_teacher, PDO::PARAM_INT);
            
            $success = $stmt->execute();
            if ($success) {
                return $this->database->lastInsertId();
            }
            
            // Log l'erreur SQL si l'exécution échoue
            $error = $stmt->errorInfo();
            error_log("Erreur SQL lors de la création du cours : " . $error[2]);
            return false;
            
        } catch(PDOException $e) {
            error_log("Exception PDO lors de la création du cours : " . $e->getMessage());
            error_log("Requête SQL : " . $query);
            error_log("Paramètres : " . print_r([
                'titre' => $this->titre,
                'description' => $this->description,
                'couverture' => $this->couverture,
                'contenu' => $this->contenu,
                'format_document' => $this->format_document,
                'taille' => $this->taille,
                'statut' => $this->status,
                'niveau' => $this->niveau,
                'id_teacher' => $id_teacher
            ], true));
            throw $e;
        }
    }

    public function getAllCourses($limit, $offset) {
        try {
            $query = "SELECT c.*, u.nom, u.prenom, u.photo, cat.nom_categorie as categorie 
                     FROM courses c
                     JOIN users u ON c.id_teacher = u.id_user
                     JOIN categories cat ON c.id_categorie = cat.id_categorie
                     WHERE c.statut_cours = 'Approuvé'
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur getAllCourses: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalCourses() {
        try {
            $query = "SELECT COUNT(*) as total FROM courses WHERE statut_cours = 'Approuvé'";
            $stmt = $this->database->query($query);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch(PDOException $e) {
            error_log("Erreur getTotalCourses: " . $e->getMessage());
            return 0;
        }
    }

    public function getCoursesByCategory($category_name, $limit, $offset) {
        try {
            $query = "SELECT c.*, u.nom, u.prenom, u.photo, cat.nom_categorie as categorie 
                     FROM courses c
                     JOIN users u ON c.id_teacher = u.id_user
                     JOIN categories cat ON c.id_categorie = cat.id_categorie
                     WHERE c.statut_cours = 'Approuvé' 
                     AND cat.nom_categorie = :category
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindParam(':category', $category_name, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur getCoursesByCategory: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalCoursesByCategory($category_name) {
        try {
            $query = "SELECT COUNT(*) as total 
                     FROM courses c
                     JOIN categories cat ON c.id_categorie = cat.id_categorie
                     WHERE c.statut_cours = 'Approuvé' 
                     AND cat.nom_categorie = :category";
            
            $stmt = $this->database->prepare($query);
            $stmt->bindParam(':category', $category_name, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch(PDOException $e) {
            error_log("Erreur getTotalCoursesByCategory: " . $e->getMessage());
            return 0;
        }
    }
}
