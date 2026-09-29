<?php

declare(strict_types=1);

require_once __DIR__ . '/../Vues/Vue.php';

abstract class Controleur
{
    protected const USER_ID_TEMP = 'a1111111-1111-1111-1111-111111111111';

    public function __construct(protected Vue $vue) {}

    protected function erreur(int $statut, string $titre, string $message): void
    {
        $this->vue->rendre('erreur', [
            'titrePage' => $titre,
            'messageErreur' => $message,
        ], $statut);
    }

    protected function rediriger(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}