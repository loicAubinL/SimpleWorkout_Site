<?php

declare(strict_types=1);

require_once __DIR__ . '/Controleur.php';

class ControleurRecits extends Controleur
{
    public function index(): void
    {
        $this->vue->rendre('recits', ['titrePage' => 'Récits Utilisateurs']);
    }
}