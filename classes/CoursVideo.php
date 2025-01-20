<?php
require_once __DIR__ . '/course.php';

class VideoCourse extends Course {
    private string $video_url;
    private string $duree;
    private string $format;

    public function __construct(
        string $titre,
        string $description,
        string $couverture,
        string $video_url,
        string $duree,
        string $format,
        string $statut,
        string $niveau
    ) {
        parent::__construct($titre, $description, $couverture, $statut, $niveau);
        $this->video_url = $video_url;
        $this->duree = $duree;
        $this->format = $format;
    }

    public function afficherDetails() {
        return [
            'type' => 'video',
            'video_info' => [
                'url' => $this->video_url,
                'duree' => $this->duree,
                'format' => $this->format
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
