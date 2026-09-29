# SimpleWorkout_Site

Application Web qui donne un accès simple et rapide à son workout du jour, à partir de plans d'entraînement créés par soi-même.

**Problème résolu :** les personnes qui notent leurs entraînements dans leurs notes finissent par s'y perdre. SimpleWorkout_Site regroupe les plans, les jours et les exercices au même endroit.

**Utilisateurs visés :** toute personne qui suit un programme de musculation (split) et veut consigner ses séries.

**Fonctions disponibles :**

- afficher le workout du jour à l'accueil;
- consulter ses workouts, leurs jours et leurs exercices;
- enregistrer et supprimer les séries effectuées (poids × répétitions);
- modifier un exercice (nom, séries, répétitions, notes);
- créer un compte, se connecter et se déconnecter.

## Prérequis

| Logiciel | Version |
|---|---|
| AMPPS | 4.4 |
| Apache | fourni par AMPPS 4.4, avec `mod_rewrite` |
| PHP | 8.2 |
| MySQL | 8.0 ou plus récent (requis pour `DEFAULT (uuid())`) |
| MySQL Workbench ou phpMyAdmin | pour importer les scripts SQL |
| VS Code + extension PHP Debug | pour le débogage avec Xdebug (port 9003) |

Extensions PHP à activer dans le fichier `php-8.2.ini` d'AMPPS (retirer le `;` au début de la ligne) :

```
extension=php_intl.dll
extension=php_mysqli.dll
extension=php_pdo_mysql.dll
extension=php_openssl.dll
extension=mbstring
```

## Installation

1. Récupérer le dépôt dans un dossier de projets, par exemple `C:/Projets` :

   ```
   git clone https://github.com/loicAubinL/SimpleWorkout_Site.git
   ```

2. Démarrer MySQL dans AMPPS.

3. Exécuter les scripts SQL dans l'ordre, avec MySQL Workbench (`127.0.0.1`, port `3306`) ou phpMyAdmin :

   - `database/schema.sql` : crée la base `simpleworkout_site` (utf8mb4) et ses tables;
   - `database/ajout-10-lignes.sql` : ajoute les données de démonstration (un split « Push Pull Legs », 3 jours, 10 exercices).

4. Vérifier que la base `simpleworkout_site` contient les tables `users`, `profiles`, `workout_splits`, `workout_days`, `exercises`, `logged_sets` et `user_progress`.

## Configuration

La connexion PDO (`config/bd.php`) lit uniquement des **variables d'environnement**. Aucun mot de passe n'est écrit dans le code ni dans le dépôt.

| Variable | Exemple |
|---|---|
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `simpleworkout_site` |
| `DB_USERNAME` | `root` |
| `DB_PASSWORD` | mot de passe du compte MySQL local |

Ces variables sont définies avec `SetEnv` dans la configuration Apache (voir Démarrage).

## Démarrage

1. Dans `httpd.conf`, vérifier que la ligne suivante n'est pas commentée :

   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

2. Dans le fichier `alias.conf` utilisé par AMPPS (`apache/conf/extra/alias.conf`), ajouter le bloc suivant en adaptant le chemin physique et le mot de passe :

   ```apache
   Alias /projet "C:/Projets/SimpleWorkout_Site"

   <Directory "C:/Projets/SimpleWorkout_Site">
       Options -Indexes +FollowSymLinks
       AllowOverride All
       Require all granted

       SetEnv DB_HOST 127.0.0.1
       SetEnv DB_PORT 3306
       SetEnv DB_DATABASE simpleworkout_site
       SetEnv DB_USERNAME root
       SetEnv DB_PASSWORD votre_mot_de_passe_mysql
   </Directory>
   ```

   `AllowOverride All` permet au fichier `.htaccess` du projet de produire les URL lisibles.

3. Redémarrer Apache et démarrer MySQL dans AMPPS.

4. Ouvrir :

   ```text
   http://localhost/projet/
   ```

5. Créer un compte avec **Créer un compte**, puis se connecter.

## Compilation

Aucune compilation n'est nécessaire : les fichiers PHP sont exécutés directement par Apache.

## Tests

Aucun test automatisé pour cette version. Vérifications manuelles :

1. `workouts` → un workout → un jour → un exercice : les URL restent lisibles (`workout/...`, `jour/...`, `exercice/...`).
2. **Modifier cet exercice** : le formulaire est prérempli; un nom vide ou 0 série affiche les erreurs en conservant les valeurs; une modification valide est enregistrée et ramène au détail.
3. Ajouter puis supprimer une série (page de confirmation, puis suppression).
4. Créer un compte : les mots de passe différents sont refusés; une création valide redirige vers la connexion.
5. Connexion avec un mauvais mot de passe : message générique. Connexion valide : le nom apparaît dans le menu. Rouvrir `connexion` redirige vers l'accueil.
6. Déconnexion : le nom disparaît du menu.
7. Erreurs : action inconnue → 404, identifiant invalide → 400, jeton CSRF absent → 403, GET sur une action POST → 405.

## Organisation du code

```
SimpleWorkout_Site/
├── .htaccess                     Réécriture des URL lisibles vers index.php
├── index.php                     Point d'entrée : assemble les objets et appelle le routeur
├── config/
│   ├── bd.php                    Connexion PDO unique (variables d'environnement)
│   ├── securite.php              Jeton CSRF
│   └── url.php                   URL de base et redirections
├── Services/
│   └── Authentification.php      Session : connexion, utilisateur courant, déconnexion
├── Routage/
│   └── Routeur.php               Liste des actions permises, validation méthode/id/CSRF
├── Modeles/
│   ├── Modele.php                Classe abstraite : conserve le PDO partagé, executer()
│   ├── WorkoutModele.php         Splits et jours d'entraînement
│   ├── ExerciceModele.php        Exercices (lecture et modification)
│   ├── LoggedSetModele.php       Séries effectuées
│   └── Utilisateur.php           Comptes
├── Controleurs/
│   ├── ControleurErreur.php      Pages d'erreur avec statut HTTP
│   ├── ControleurAccueil.php     Workout du jour
│   ├── ControleurRecits.php      Page des récits
│   ├── ControleurWorkout.php     Workouts et jours
│   ├── ControleurExercice.php    Modification d'un exercice
│   ├── ControleurLoggedSet.php   Séries : liste, ajout, suppression
│   └── ControleurUtilisateur.php Création de compte, connexion, déconnexion
├── Vues/
│   ├── Vue.php                   Rendu centralisé : vue + gabarit
│   ├── gabarit.php               Gabarit commun (menu, personne connectée)
│   ├── accueil.php, erreur.php, recits.php
│   ├── workout/                  liste.php, detail.php
│   ├── jour/                     detail.php
│   ├── exercice/                 detail.php, modifier.php
│   ├── logged-set/               liste.php (sous-vue), confirmer-suppression.php
│   └── utilisateurs/             connexion.php, inscription.php
├── css/style.css
├── database/                     schema.sql, ajout-10-lignes.sql
└── .vscode/launch.json           Configuration Xdebug
```

Tous les modèles héritent de `Modele` et reçoivent le **même objet PDO**, créé une seule fois dans `index.php`. Les contrôleurs reçoivent leurs modèles, la `Vue` et le `ControleurErreur` par leur constructeur.

## Routes disponibles

| Méthode | URL (après `/projet/`) | Action |
|---|---|---|
| GET | *(vide)* | Accueil : workout du jour |
| GET | `recits` | Récits utilisateurs |
| GET | `workouts` | Liste des workouts |
| GET | `workout/{id}` | Détail d'un workout (ses jours) |
| GET | `jour/{id}` | Détail d'un jour (ses exercices) |
| GET | `exercice/{id}` | Détail d'un exercice (ses séries) |
| GET | `exercice-modifier/{id}` | Formulaire de modification prérempli |
| POST | `exercice-enregistrer` | Enregistre la modification |
| POST | `logged-set-ajouter` | Ajoute une série |
| GET | `logged-set-supprimer/{id}` | Confirmation de suppression |
| POST | `logged-set-supprimer-confirme` | Supprime la série |
| GET | `inscription` | Page de création de compte |
| POST | `creer-compte` | Crée le compte |
| GET | `connexion` | Page de connexion |
| POST | `authentifier` | Connecte la personne |
| POST | `deconnexion` | Déconnecte la personne |

Toutes les actions POST exigent un jeton CSRF valide. Les erreurs retournent 400, 403, 404, 405 ou 500, sans détails techniques.

## Historique des jalons

- **projet-chapitre-01** : dépôt, modèle entité-relation, base MySQL, alias Apache, pages d'accueil et de récits.
- **projet-chapitre-02** : connexion PDO centralisée, première architecture MVC, données de démonstration.
- **projet-chapitre-03** : contrôleur frontal, table reliée `logged_sets`, ajout et suppression de séries, CSRF, codes HTTP, Xdebug.
- **projet-chapitre-04** : conversion orientée objet (`Modele`, `Vue`, `Routeur`), URL lisibles, récit « modifier un exercice », création de compte et authentification.