@extends('layouts.app')

@section('title', 'Récits')

@php
$recitsUtilisateur = [
['texte' => 'accéder à tous mes entraînements afin de pouvoir les gérer.', 'complete' => true, 'criteres' => [
'La liste affiche seulement mes workouts, 10 par page.',
'Chaque workout affiche son nombre de jours et s\'il est actif.',
'Le détail affiche les jours ; un workout sans jour affiche un message.',
'Un workout d\'un autre utilisateur ou inexistant donne une erreur 404.',
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
'Les jours du workout sont supprimés avec lui.',
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
['texte' => 'modifier mon compte afin de pouvoir gérer mes informations.', 'complete' => false, 'criteres' => []],
['texte' => 'supprimer mon compte afin de pouvoir gérer mes informations.', 'complete' => false, 'criteres' => []],
['texte' => 'créer un exercice afin de pouvoir gérer mes exercices.', 'complete' => false, 'criteres' => []],
['texte' => 'modifier un exercice afin de pouvoir gérer mes exercices.', 'complete' => false, 'criteres' => []],
['texte' => 'supprimer un exercice afin de pouvoir gérer mes exercices.', 'complete' => false, 'criteres' => []],
['texte' => 'accéder à tous mes exercices afin de pouvoir gérer mes exercices.', 'complete' => false, 'criteres' => []],
];

$recitsAdmin = [
'accéder à tous les comptes utilisateurs afin de pouvoir les gérer.',
'supprimer un compte utilisateur afin de pouvoir gérer les utilisateurs.',
'modifier un compte utilisateur afin de pouvoir gérer les utilisateurs.',
'créer un compte utilisateur afin de pouvoir gérer les utilisateurs.',
'modifier les entraînements d\'un utilisateur afin de pouvoir gérer les utilisateurs.',
'supprimer les entraînements d\'un utilisateur afin de pouvoir gérer les utilisateurs.',
'créer des entraînements pour un utilisateur afin de pouvoir gérer les utilisateurs.',
'modifier les exercices d\'un utilisateur afin de pouvoir gérer les utilisateurs.',
'supprimer les exercices d\'un utilisateur afin de pouvoir gérer les utilisateurs.',
'créer des exercices pour un utilisateur afin de pouvoir gérer les utilisateurs.',
'accéder à tous les entraînements d\'un utilisateur afin de pouvoir gérer les utilisateurs.',
'accéder à tous les exercices d\'un utilisateur afin de pouvoir gérer les utilisateurs.',
];
@endphp

@section('content')
<h1>Récits utilisateurs</h1>
<p>Fonctionnalités attendues du projet SimpleWorkout.</p>

<div class="grille-recits">
    <div class="colonne-recits">
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

    <div class="colonne-recits">
        <h2>Récits administrateur</h2>
        <ul class="liste-recits">
            @foreach ($recitsAdmin as $recit)
            <li><strong>En tant qu'admin</strong>, je veux pouvoir {{ $recit }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection