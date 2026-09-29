<?php

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
    "Créer un compte afin de pouvoir utiliser l'application. — Complété",
    "Me connecter à mon compte afin de pouvoir utiliser l'application. — Complété",
    "Modifier mon compte afin de pouvoir gérer mes informations.",
    "Supprimer mon compte afin de pouvoir gérer mes informations.",
    "Créer un entraînement afin de pouvoir gérer mes entraînements.",
    "Modifier un entraînement afin de pouvoir gérer mes entraînements.",
    "Supprimer un entraînement afin de pouvoir gérer mes entraînements.",
    "Créer un exercice afin de pouvoir gérer mes exercices.",
    "Modifier un exercice afin de pouvoir gérer mes exercices. — Complété",
    "Supprimer un exercice afin de pouvoir gérer mes exercices.",
    "Accéder à tous mes entraînements afin de pouvoir gérer mes entraînements.",
    "Accéder à tous mes exercices afin de pouvoir gérer mes exercices."
];
?>

<section class="recits-container">
    <h1><?= htmlspecialchars($titrePage ?: 'Récits Utilisateurs', ENT_QUOTES, 'UTF-8') ?></h1>
    <p>Liste des fonctionnalités attendues (User Stories) pour le projet SimpleWorkout.</p>

    <div class="grille-recits">

        <div class="colonne-recits">
            <h2>Récits Administrateur</h2>
            <ul class="liste-recits">
                <?php foreach ($recitsAdmin as $recit): ?>
                    <li>
                        <strong>En tant qu'admin</strong>, je veux pouvoir <?= htmlspecialchars($recit, ENT_QUOTES, 'UTF-8') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="colonne-recits">
            <h2>Récits Utilisateur</h2>
            <ul class="liste-recits">
                <?php foreach ($recitsUtilisateur as $recit): ?>
                    <?php
                    $complete = str_ends_with($recit, '— Complété');
                    $texte = $complete ? trim(str_replace('— Complété', '', $recit)) : $recit;
                    ?>
                    <li class="<?= $complete ? 'recit-complete' : '' ?>">
                        <strong>En tant qu'utilisateur</strong>, je veux pouvoir <?= htmlspecialchars($texte, ENT_QUOTES, 'UTF-8') ?>
                        <?php if ($complete): ?>
                            <span class="badge-complete">✓ Complété</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</section>