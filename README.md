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

Vérifier les valeurs de `.env` (base de données, identifiants), puis :

```powershell
docker compose config --quiet
docker compose up --build -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

> `key:generate` seulement à la première installation.

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

## Récits

Voir [docs/recits.md](docs/recits.md).
