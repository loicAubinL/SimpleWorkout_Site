# SimpleWorkout_Site

Application de suivi d'entraînement, refaite avec Laravel 12 dans Docker (PHP 8.2, Apache, MySQL 8.4).

## Prérequis

- Git
- Docker Desktop (démarré)

## Installation sur un nouveau poste

```powershell
git clone https://github.com/loicAubinL/SimpleWorkout_Site.git
cd SimpleWorkout_Site
Copy-Item .env.example .env
```

> Sur macOS ou Linux : `cp .env.example .env`

Vérifier les valeurs de `.env` (base de données, identifiants, clés reCAPTCHA), puis :

```powershell
docker compose config --quiet
docker compose up --build -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
```

> `key:generate` seulement à la première installation.
> `storage:link` rend accessibles les fichiers programme (JSON) téléversés.

## Utilisation

- Site : http://localhost:8080
- MySQL (Workbench) : `127.0.0.1:3307`, identifiants de `.env`
    > Dans `.env`, garder `DB_HOST=db` et `DB_PORT=3306` : c'est le port interne au réseau Docker.
    > Le port `3307` sert seulement à se connecter depuis l'hôte (Workbench).
- État des migrations : `docker compose exec app php artisan migrate:status`

## Arrêter et redémarrer

```powershell
docker compose down
docker compose up -d
```

`docker compose down -v` supprime aussi les données MySQL : à éviter sauf pour repartir d'une base vide.

## Données de démonstration

Sur une base locale jetable (efface toutes les données) :

```powershell
docker compose exec app php artisan migrate:fresh --seed
```

Crée deux comptes de test, 13 workouts pour le compte démo (dont un sans jour) et un workout appartenant à un autre utilisateur.

Des fichiers JSON de démonstration (valides et invalides) sont dans `docs/demo/`.

## Comptes de test

Après `migrate:fresh --seed` (mot de passe : `password`) :

| Courriel                   | Rôle                                    |
| -------------------------- | --------------------------------------- |
| `demo@example.test`        | Membre                                  |
| `responsable@example.test` | Responsable (accès à `/administration`) |

## reCAPTCHA

Créer une paire de clés reCAPTCHA v2 (case « Je ne suis pas un robot », domaine `localhost`),
puis les placer dans `.env` (jamais dans Git) :

```dotenv
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

Puis : `docker compose exec app php artisan config:clear`

## Rôles et accès

- Visiteur : accueil, workouts publics, détail d'un workout, récits, connexion, inscription.
- Membre : en plus, Mes workouts, Mon compte, recherche, créer ; modifier et supprimer **ses** workouts seulement (sinon 403).
- Responsable : en plus, la section `/administration`.

## Routes principales

| Méthode | Adresse                          | Action                                     | Accès                         |
| ------- | -------------------------------- | ------------------------------------------ | ----------------------------- |
| GET     | `/`                              | Accueil (workout actif + derniers publics) | Public                        |
| GET     | `/workouts-publics`              | Liste paginée de tous les workouts         | Public                        |
| GET     | `/recits`                        | Page des récits                            | Public                        |
| POST    | `/langue`                        | Changer la langue (fr / en)                | Public                        |
| GET     | `/inscription`                   | Formulaire d'inscription                   | Non connecté (`guest`)        |
| POST    | `/inscription`                   | Création du compte (reCAPTCHA)             | Non connecté (`guest`)        |
| GET     | `/connexion`                     | Formulaire de connexion                    | Non connecté (`guest`)        |
| POST    | `/connexion`                     | Connexion (6 essais par minute)            | Non connecté (`guest`)        |
| POST    | `/deconnexion`                   | Déconnexion                                | Connecté                      |
| GET     | `/compte`                        | Mon compte                                 | Connecté                      |
| GET     | `/workout-splits`                | Mes workouts (liste paginée)               | Connecté                      |
| GET     | `/workout-splits/create`         | Formulaire de création                     | Connecté                      |
| POST    | `/workout-splits`                | Création                                   | Connecté                      |
| GET     | `/workout-splits/{id}`           | Détail                                     | Public                        |
| GET     | `/workout-splits/{id}/edit`      | Formulaire de modification                 | Propriétaire (policy)         |
| PATCH   | `/workout-splits/{id}`           | Modification                               | Propriétaire (policy)         |
| DELETE  | `/workout-splits/{id}`           | Suppression                                | Propriétaire (policy)         |
| DELETE  | `/workout-splits/{id}/programme` | Retirer le fichier programme               | Propriétaire (policy)         |
| GET     | `/recherche/workouts?q=`         | Recherche JSON par nom de jour             | Connecté                      |
| GET     | `/administration`                | Liste des comptes                          | Responsable (`EnsureManager`) |

Vérifier la liste réelle : `docker compose exec app php artisan route:list --except-vendor`
