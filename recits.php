<?php

ob_start();

$recitsAdmin = [
    "Accéder à tous les comptes utilisateurs afin de pouvoir les gérer.",
    "Supprimer un compte utilisateur afin de pouvoir gérer les utilisateurs.",
    "Modifier un compte utilisateur afin de pouvoir gérer les utilisateurs.",
    "Créer un compte utilisateur afin de pouvoir gérer les utilisateurs.",
    "Modifier les entraînements d'un utilisateur afin de pouvoir gérer les utilisateurs.",
    "Supprimer les entraînements d'un utilisateur afin de pouvoir gérer les utilisateurs.",
    "Créer des entraînements pour un utilisateur afin de pouvoir gérer les utilisateurs.",
    "Modifier les exercices d'un utilisateur afin de pouvoir gérer les utilisateurs.",
    "Supprimer les exercices d'un utilisateur afin de pouvoir gérer les utilisateurs.",
    "Créer des exercices pour un utilisateur afin de pouvoir gérer les utilisateurs.",
    "Accéder à tous les entraînements d'un utilisateur afin de pouvoir gérer les utilisateurs.",
    "Accéder à tous les exercices d'un utilisateur afin de pouvoir gérer les utilisateurs."
];

$recitsUtilisateur = [
    "Créer un compte afin de pouvoir utiliser l'application.",
    "Me connecter à mon compte afin de pouvoir utiliser l'application.",
    "Modifier mon compte afin de pouvoir gérer mes informations.",
    "Supprimer mon compte afin de pouvoir gérer mes informations.",
    "Créer un entraînement afin de pouvoir gérer mes entraînements.",
    "Modifier un entraînement afin de pouvoir gérer mes entraînements.",
    "Supprimer un entraînement afin de pouvoir gérer mes entraînements.",
    "Créer un exercice afin de pouvoir gérer mes exercices.",
    "Modifier un exercice afin de pouvoir gérer mes exercices.",
    "Supprimer un exercice afin de pouvoir gérer mes exercices.",
    "Accéder à tous mes entraînements afin de pouvoir gérer mes entraînements.",
    "Accéder à tous mes exercices afin de pouvoir gérer mes exercices."
];
?>

<section class="recits-container">
    <h1><?= htmlspecialchars($titrePage ?? 'Récits Utilisateurs', ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Liste des fonctionnalités attendues (User Stories) pour le projet SimpleWorkout.</p>

    <h2>Récits Administrateur</h2>
    <ul class="liste-recits">
        <?php foreach ($recitsAdmin as $index => $recit): ?>
            <li>
                <strong>En tant qu'admin</strong>, je veux pouvoir <?= htmlspecialchars($recit, ENT_QUOTES, 'UTF-8') ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Récits Utilisateur</h2>
    <ul class="liste-recits">
        <?php foreach ($recitsUtilisateur as $index => $recit): ?>
            <li>
                <strong>En tant qu'utilisateur</strong>, je veux pouvoir <?= htmlspecialchars($recit, ENT_QUOTES, 'UTF-8') ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/Vues/gabarit.php';