<?php

declare(strict_types=1);

require_once __DIR__ . '/Modele.php';

class Utilisateur extends Modele
{
    public function trouverParEmail(string $email): ?array
    {
        return $this->executer(
            'SELECT id, name, email, password_hash FROM users WHERE email = :email',
            ['email' => $email]
        )->fetch() ?: null;
    }

    public function creer(string $nom, string $email, string $hachage): void
    {
        $this->executer(
            'INSERT INTO users (name, email, password_hash) VALUES (:nom, :email, :hachage)',
            ['nom' => $nom, 'email' => $email, 'hachage' => $hachage]
        );
    }
}
