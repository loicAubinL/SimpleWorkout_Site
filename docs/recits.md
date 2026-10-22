# Récits utilisateurs — SimpleWorkout

Fonctionnalités attendues du projet. Aucune n'est encore reconstruite dans la version Laravel.

## Récits administrateur

En tant qu'admin, je veux pouvoir :

- accéder à tous les comptes utilisateurs afin de pouvoir les gérer;
- supprimer un compte utilisateur afin de pouvoir gérer les utilisateurs;
- modifier un compte utilisateur afin de pouvoir gérer les utilisateurs;
- créer un compte utilisateur afin de pouvoir gérer les utilisateurs;
- modifier les entraînements d'un utilisateur afin de pouvoir gérer les utilisateurs;
- supprimer les entraînements d'un utilisateur afin de pouvoir gérer les utilisateurs;
- créer des entraînements pour un utilisateur afin de pouvoir gérer les utilisateurs;
- modifier les exercices d'un utilisateur afin de pouvoir gérer les utilisateurs;
- supprimer les exercices d'un utilisateur afin de pouvoir gérer les utilisateurs;
- créer des exercices pour un utilisateur afin de pouvoir gérer les utilisateurs;
- accéder à tous les entraînements d'un utilisateur afin de pouvoir gérer les utilisateurs;
- accéder à tous les exercices d'un utilisateur afin de pouvoir gérer les utilisateurs.

## Récits utilisateur

En tant qu'utilisateur, je veux pouvoir :

- créer un compte afin de pouvoir utiliser l'application;
- me connecter à mon compte afin de pouvoir utiliser l'application;
- modifier mon compte afin de pouvoir gérer mes informations;
- supprimer mon compte afin de pouvoir gérer mes informations;
- créer un entraînement afin de pouvoir gérer mes entraînements;
- modifier un entraînement afin de pouvoir gérer mes entraînements;
- supprimer un entraînement afin de pouvoir gérer mes entraînements;
- créer un exercice afin de pouvoir gérer mes exercices;
- modifier un exercice afin de pouvoir gérer mes exercices;
- supprimer un exercice afin de pouvoir gérer mes exercices;
- accéder à tous mes entraînements afin de pouvoir gérer mes entraînements;
- accéder à tous mes exercices afin de pouvoir gérer mes exercices.

## Récits réalisés en Laravel

### ✅ Complété — Accéder à tous mes entraînements

En tant qu'utilisateur, je veux accéder à tous mes entraînements afin de pouvoir les gérer.

- La liste affiche seulement mes workouts, 10 par page.
- Chaque workout affiche son nombre de jours et s'il est actif.
- Le détail d'un workout affiche ses jours ; un workout sans jour affiche un message.
- Un workout d'un autre utilisateur ou inexistant donne une erreur 404.

### ✅ Complété — Créer un entraînement

En tant qu'utilisateur, je veux créer un entraînement afin de pouvoir gérer mes entraînements.

- Le nom est obligatoire (255 caractères maximum).
- En cas d'erreur, un message s'affiche et la saisie est conservée.
- Après la création, je suis redirigé vers le détail avec un message de confirmation.
- Un seul workout peut être actif à la fois.

### ✅ Complété — Modifier un entraînement

En tant qu'utilisateur, je veux modifier un entraînement afin de pouvoir gérer mes entraînements.

- Le formulaire est prérempli avec les valeurs actuelles.
- Les mêmes règles de validation que la création s'appliquent.
- Activer un workout désactive les autres (transaction).

### ✅ Complété — Supprimer un entraînement

En tant qu'utilisateur, je veux supprimer un entraînement afin de pouvoir gérer mes entraînements.

- La suppression passe par un formulaire (méthode DELETE), jamais par un lien.
- Les jours du workout sont supprimés avec lui.
- Je suis redirigé vers la liste avec un message de confirmation.
