@extends('layouts.app')

@section('title', 'Récits')

@php
$recitsUtilisateur = [
['texte' => 'accéder à tous mes entraînements afin de pouvoir les gérer.', 'complete' => true, 'criteres' => [
'La liste affiche seulement mes workouts, 12 par page.',
'Chaque workout affiche son nombre de jours et d\'exercices, et s\'il est actif et public ou privé.',
'Le détail affiche les jours et leurs exercices ; un workout sans jour affiche un message.',
'Le workout privé d\'un autre utilisateur donne une erreur 403 ; un workout inexistant, une erreur 404.',
]],
['texte' => 'créer un entraînement afin de pouvoir gérer mes entraînements.', 'complete' => true, 'criteres' => [
'Le nom est obligatoire (255 caractères maximum).',
'En cas d\'erreur, un message s\'affiche et la saisie est conservée.',
'Après la création, redirection vers le détail avec un message.',
'Un seul workout peut être actif à la fois.',
]],
['texte' => 'modifier un entraînement afin de pouvoir gérer mes entraînements.', 'complete' => true, 'criteres' => [
'Le formulaire est prérempli avec les valeurs actuelles.',
'Les mêmes règles de validation que la création s\'appliquent.',
'Activer un workout désactive les autres (transaction).',
'Seul le propriétaire du workout peut le modifier ou le supprimer ; un autre compte reçoit 403.',
]],
['texte' => 'supprimer un entraînement afin de pouvoir gérer mes entraînements.', 'complete' => true, 'criteres' => [
'La suppression passe par un formulaire DELETE, jamais par un lien.',
'Les jours, les exercices et les séries du workout sont supprimés avec lui.',
'Redirection vers la liste avec un message.',
]],
['texte' => 'créer un compte afin de pouvoir utiliser l\'application.', 'complete' => true, 'criteres' => [
'Le nom, le courriel et sa confirmation, le mot de passe et sa confirmation sont validés côté serveur.',
'reCAPTCHA est vérifié côté serveur ; une vérification absente ou refusée ne crée aucun compte.',
'Le nouveau compte reçoit le rôle membre, sans pouvoir le choisir.',
]],
['texte' => 'me connecter à mon compte afin de pouvoir utiliser l\'application.', 'complete' => true, 'criteres' => [
'Des identifiants invalides affichent un message, sans préciser lequel est faux.',
'Après 6 tentatives en une minute, la connexion est temporairement refusée.',
'Une personne connectée ne revoit pas le formulaire de connexion et peut se déconnecter.',
]],
['texte' => 'modifier mon compte afin de pouvoir gérer mes informations.', 'complete' => true, 'criteres' => [
'Le nom et le courriel sont modifiables ; un courriel déjà utilisé est refusé.',
'Changer le mot de passe exige le mot de passe actuel et une confirmation (8 caractères minimum).',
'Le rôle ne peut pas être modifié par l\'utilisateur.',
]],
['texte' => 'supprimer mon compte afin de pouvoir gérer mes informations.', 'complete' => true, 'criteres' => [
'La suppression exige le mot de passe actuel et une confirmation.',
'Les workouts, exercices, séries et sauvegardes du compte sont supprimés avec lui.',
'La personne est déconnectée ; le dernier responsable ne peut pas supprimer son compte.',
]],
['texte' => 'créer un exercice afin de pouvoir gérer mes exercices.', 'complete' => true, 'criteres' => [
'Un exercice appartient à un jour d\'un de mes workouts.',
'Nom, séries et répétitions obligatoires ; charge, repos, RIR et notes facultatifs, validés côté serveur.',
'Seul le propriétaire du workout peut ajouter un exercice ; un autre compte reçoit 403.',
]],
['texte' => 'modifier un exercice afin de pouvoir gérer mes exercices.', 'complete' => true, 'criteres' => [
'Le formulaire est prérempli et applique les mêmes validations que la création.',
'L\'ordre des exercices d\'un jour peut être changé (monter / descendre).',
'Seul le propriétaire du workout peut le modifier ; un autre compte reçoit 403.',
]],
['texte' => 'supprimer un exercice afin de pouvoir gérer mes exercices.', 'complete' => true, 'criteres' => [
'La suppression passe par un formulaire DELETE avec confirmation.',
'Les séries enregistrées de l\'exercice sont supprimées avec lui.',
'Seul le propriétaire du workout peut le supprimer ; un autre compte reçoit 403.',
]],
['texte' => 'accéder à tous mes exercices afin de pouvoir gérer mes exercices.', 'complete' => true, 'criteres' => [
'Le détail d\'un workout affiche chaque jour et ses exercices (séries × répétitions, charge, repos, RIR).',
'L\'historique liste les exercices suivis avec leur record ; chaque exercice a sa page de progression.',
'La recherche de mes workouts trouve aussi un workout par le nom d\'un exercice.',
]],
];

$recitsAdmin = [
['texte' => 'accéder à tous les comptes utilisateurs afin de pouvoir les gérer.', 'complete' => true, 'criteres' => [
'La liste paginée des comptes est réservée au responsable (middleware EnsureManager, sinon 403).',
'Recherche par nom ou courriel et filtre par rôle.',
]],
['texte' => 'supprimer un compte utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Suppression par un formulaire DELETE avec confirmation ; les données du compte suivent.',
'Impossible de supprimer son propre compte ici ou le dernier responsable.',
]],
['texte' => 'modifier un compte utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Nom, courriel, rôle et mot de passe (facultatif) modifiables, validés côté serveur.',
'Le dernier responsable ne peut pas être rétrogradé.',
]],
['texte' => 'créer un compte utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Nom, courriel unique, rôle et mot de passe confirmé obligatoires.',
]],
['texte' => 'modifier les entraînements d\'un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'La policy autorise le responsable à modifier tout workout, même privé.',
]],
['texte' => 'supprimer les entraînements d\'un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Après la suppression, retour à la fiche du compte concerné.',
]],
['texte' => 'créer des entraînements pour un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Depuis la fiche du compte, le workout créé appartient à ce compte.',
]],
['texte' => 'modifier les exercices d\'un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Le responsable a les mêmes outils que le propriétaire sur le détail du workout.',
]],
['texte' => 'supprimer les exercices d\'un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Suppression par un formulaire DELETE avec confirmation.',
]],
['texte' => 'créer des exercices pour un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Ajout d\'exercices dans n\'importe quel jour d\'un workout du compte.',
]],
['texte' => 'accéder à tous les entraînements d\'un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'La fiche du compte liste tous ses workouts, publics et privés.',
]],
['texte' => 'accéder à tous les exercices d\'un utilisateur afin de pouvoir gérer les utilisateurs.', 'complete' => true, 'criteres' => [
'Le détail de chaque workout du compte affiche ses jours et ses exercices.',
]],
];
@endphp

@section('content')
<div class="entete-page">
    <div>
        <span class="surtitre">SimpleWorkout</span>
        <h1>Récits utilisateurs</h1>
        <p class="sous-titre">Fonctionnalités attendues du projet SimpleWorkout.</p>
    </div>
</div>

<div class="grille-recits">
    <div class="colonne-recits carte">
        <h2>Récits utilisateur</h2>
        <ul class="liste-recits">
            @foreach ($recitsUtilisateur as $recit)
            <li class="{{ $recit['complete'] ? 'recit-complete' : '' }}">
                <strong>En tant qu'utilisateur</strong>, je veux pouvoir {{ $recit['texte'] }}
                @if ($recit['complete'])
                <span class="badge-complete">Complété</span>
                @endif
                @if ($recit['criteres'])
                <ul class="criteres">
                    @foreach ($recit['criteres'] as $critere)
                    <li>{{ $critere }}</li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>

    <div class="colonne-recits carte">
        <h2>Récits administrateur</h2>
        <ul class="liste-recits">
            @foreach ($recitsAdmin as $recit)
            <li class="{{ $recit['complete'] ? 'recit-complete' : '' }}">
                <strong>En tant qu'admin</strong>, je veux pouvoir {{ $recit['texte'] }}
                @if ($recit['complete'])
                <span class="badge-complete">Complété</span>
                @endif
                @if ($recit['criteres'])
                <ul class="criteres">
                    @foreach ($recit['criteres'] as $critere)
                    <li>{{ $critere }}</li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection