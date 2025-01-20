<?php

class Categorie {
    private string $categorie_name;
    private string $categorie_description;

    public function __construct(string $categorie_name, string $categorie_description) {
        $this->categorie_name = $categorie_name;
        $this->categorie_description = $categorie_description;
    }

    public function getCategorieName(): string {
        return $this->categorie_name;
    }

    public function getCategorieDescription(): string {
        return $this->categorie_description;
    }

    public function creerCategory(): bool {
        // Implementation for creating a new category
        return true;
    }

    public function modifierCategory(): bool {
        // Implementation for modifying an existing category
        return true;
    }

    public function supprimerCategory(): bool {
        // Implementation for deleting a category
        return true;
    }
}
