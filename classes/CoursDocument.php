<?php
require_once __DIR__ . '/course.php';

class DocumentCourse extends Course {
    private string $contenu;
    private string $format_document;
    private int $taille;

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
}
