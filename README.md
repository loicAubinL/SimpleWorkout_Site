# SimpleWorkout_Site

> [!IMPORTANT]
> **Branche `test-claude-code` : test personnel de Claude Code, pas une remise scolaire.**
> Cette branche sert à essayer Claude Code sur le projet. Elle n'est **pas** une remise
> du cours 420-5H6-MO et ne doit pas être fusionnée dans `main`. Les remises officielles
> restent sur `main` et sur les branches et étiquettes `projet-chapitre-XX`, qui n'ont pas été modifiées.

Application de suivi d'entraînement faite avec Laravel 12 dans Docker (PHP 8.2, Apache, MySQL 8.4) :
splits → jours → exercices, séances avec séries enregistrées, historique et progression,
workouts publics à sauvegarder ou copier, et administration des comptes.

## Prérequis

- Git
- Docker Desktop (démarré)

## Installation sur un nouveau poste

```powershell
git clone https://github.com/loicAubinL/SimpleWorkout_Site.git
cd SimpleWorkout_Site
git switch test-claude-code
Copy-Item .env.example .env
```

> Sur macOS ou Linux : `cp .env.example .env`

Vérifier les valeurs de `.env` (base de données, identifiants, clés reCAPTCHA), puis :

```powershell
docker compose config --quiet
docker compose up --build -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

> `key:generate` seulement à la première installation.
> Aucun build front-end n'est nécessaire : le CSS (`public/css/app.css`), la police et les icônes sont servis tels quels.

### Migrations et données de démonstration

| Commande | Effet |
| --- | --- |
| `docker compose exec app php artisan migrate` | Applique les nouvelles migrations (garde les données) |
| `docker compose exec app php artisan db:seed` | Ajoute les données de démo (sur une base vide) |
| `docker compose exec app php artisan migrate:fresh --seed` | **Efface toute la base**, puis recrée tables et démo |
| `docker compose exec app php artisan migrate:status` | État des migrations |

Le seeder crée trois comptes, des splits réalistes (Push Pull Legs, Upper / Lower, Full Body, etc.)
avec leurs jours et exercices, un workout sauvegardé et six semaines d'historique de séances pour le compte démo.

> **Base séparée pour tester sans toucher à la vôtre** : créer une base vide
> (ex. `simpleworkout_claude`) dans MySQL, mettre `DB_DATABASE=simpleworkout_claude` dans `.env`,
> puis `php artisan config:clear` et `php artisan migrate --seed`.

## Comptes de test

Après le seeder (mot de passe : `password`) :

| Courriel                   | Rôle        | Contenu                                                                 |
| -------------------------- | ----------- | ----------------------------------------------------------------------- |
| `demo@example.test`        | Membre      | Split actif PPL (public), Full Body (privé), historique, 1 sauvegarde   |
| `alex@example.test`        | Membre      | Upper / Lower et Bras express (publics), un workout privé               |
| `responsable@example.test` | Responsable | Accès à `/administration`                                               |

## Utilisation

- Site : http://localhost:8080
- MySQL (Workbench) : `127.0.0.1:3307`, identifiants de `.env`
    > Dans `.env`, garder `DB_HOST=db` et `DB_PORT=3306` : c'est le port interne au réseau Docker.
    > Le port `3307` sert seulement à se connecter depuis l'hôte (Workbench).

## Tests

```powershell
docker compose exec app php artisan test
```

39 tests Feature (SQLite en mémoire, aucune donnée MySQL touchée) : inscription avec reCAPTCHA simulé,
connexion, CRUD des workouts, jours et exercices, policies (403), workouts privés et publics,
sauvegardes et copie, séances et progression, Mon compte, administration et ses garde-fous.

## Arrêter et redémarrer

```powershell
docker compose down
docker compose up -d
```

`docker compose down -v` supprime aussi les données MySQL : à éviter sauf pour repartir d'une base vide.

## reCAPTCHA

Créer une paire de clés reCAPTCHA v2 (case « Je ne suis pas un robot », domaine `localhost`),
puis les placer dans `.env` (jamais dans Git) :

```dotenv
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

Puis : `docker compose exec app php artisan config:clear`

## Fonctionnalités et accès

- **Visiteur** : accueil, workouts publics (avec recherche), détail d'un workout **public**, récits, connexion, inscription.
- **Membre** :
    - CRUD complet de ses workouts (splits), de leurs jours et de leurs exercices (séries, répétitions, charge, repos, RIR, notes), avec réordonnancement ;
    - un workout actif à la fois : il donne le **workout du jour** à l'accueil ;
    - un workout est **privé** par défaut, et peut être rendu **public** ;
    - **sauvegarder / retirer** un workout public (le sien ou celui d'un autre), et le **copier** dans ses workouts pour le modifier ;
    - **séance** : enregistrer ses séries, puis « Terminer » fait passer le cycle au jour suivant ;
    - **historique** des séances et **progression** par exercice (meilleure charge, volume, 1RM estimé, graphique) ;
    - **Mon compte** : modifier son nom, son courriel et son mot de passe, ou supprimer son compte.
- **Responsable** : en plus, `/administration` : consulter, créer, modifier (rôle compris) et supprimer
  tous les comptes ; consulter, modifier et supprimer leurs workouts, jours et exercices ; créer un workout pour un compte.
  Garde-fous : impossible de supprimer son propre compte depuis l'administration ou de retirer le dernier responsable.

Sécurité : validation serveur (FormRequests), jeton CSRF sur chaque formulaire, autorisation par la policy
`WorkoutSplitPolicy` (propriétaire, public/privé, responsable) et le middleware `EnsureManager`,
valeurs échappées par Blade.

## Routes principales

| Méthode     | Adresse                                       | Action                                   | Accès                   |
| ----------- | --------------------------------------------- | ---------------------------------------- | ----------------------- |
| GET         | `/`                                           | Accueil (workout du jour si connecté)    | Public                  |
| GET         | `/workouts-publics?q=`                        | Workouts publics avec recherche          | Public                  |
| GET         | `/recits`                                     | Récits                                   | Public                  |
| POST        | `/langue`                                     | Changer la langue (fr / en)              | Public                  |
| GET / POST  | `/inscription`, `/connexion`                  | Inscription (reCAPTCHA), connexion       | Non connecté            |
| POST        | `/deconnexion`                                | Déconnexion                              | Connecté                |
| resource    | `/workout-splits`                             | CRUD des workouts (détail : policy view) | Connecté / propriétaire |
| PATCH       | `/workout-splits/{id}/activer`                | Définir comme actif                      | Propriétaire            |
| POST        | `/workout-splits/{id}/copier`                 | Copier dans mes workouts                 | Connecté (policy copy)  |
| POST/DELETE | `/workout-splits/{id}/sauvegarde`             | Sauvegarder / retirer                    | Connecté (policy save)  |
| GET         | `/sauvegardes`                                | Mes workouts sauvegardés                 | Connecté                |
| resource    | `/workout-splits/{id}/days`, `/days/{id}`     | CRUD des jours (+ `/deplacer`)           | Propriétaire            |
| resource    | `/days/{id}/exercises`, `/exercises/{id}`     | CRUD des exercices (+ `/deplacer`)       | Propriétaire            |
| GET         | `/seance/{id}?jour=`                          | Séance du jour                           | Propriétaire            |
| POST        | `/exercises/{id}/series`, `/seance/{id}/terminer` | Enregistrer une série, terminer       | Propriétaire            |
| DELETE      | `/series/{id}`                                | Supprimer une série                      | Auteur de la série      |
| GET         | `/historique`, `/progression/{exercice}`      | Historique et progression                | Connecté / propriétaire |
| GET         | `/recherche/workouts?q=`                      | Recherche JSON dans mes workouts         | Connecté                |
| GET/PATCH/PUT/DELETE | `/compte`, `/compte/mot-de-passe`    | Mon compte                               | Connecté                |
| resource    | `/administration/comptes`                     | CRUD des comptes                         | Responsable             |
| GET / POST  | `/administration/comptes/{id}/workouts`       | Créer un workout pour un compte          | Responsable             |

Vérifier la liste réelle : `docker compose exec app php artisan route:list --except-vendor`

## Crédits

- Logo SimpleWorkout : SVG fait maison (`resources/views/components/logo-mark.blade.php`, `public/favicon.svg`).
- Icônes : [Lucide](https://lucide.dev) v0.460.0, licence ISC (`public/vendor/lucide/LICENSE`), intégrées en SVG.
- Police des titres : Barlow Condensed, SIL Open Font License (`public/fonts/OFL.txt`), hébergée localement.
