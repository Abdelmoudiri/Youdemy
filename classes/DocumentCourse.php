<?php
require_once __DIR__ . '/course.php';

class DocumentCourse extends Course {
    private string $contenu;
    private string $format_document;
    private int $taille;
    protected $database;

    public function __construct(
        string $titre,
        string $description,
        string $couverture,
        string $contenu,
        string $format_document,
        int $taille,
        string $statut,
        string $niveau
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
            $query = "INSERT INTO cours (titre, description, couverture, contenu, format_document, taille, statut_cours, niveau, id_teacher, date_creation) 
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
            return false;
        } catch(PDOException $e) {
            error_log("Erreur lors de la création du cours : " . $e->getMessage());
            return false;
        }
    }
}
