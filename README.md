1. Nom et description du projet

Nom : SimpleWorkout_Site

Description :
Un site permettant d'avoir accès à des plan d'entrainement créer par soit meme. Le but est un accès simple et rapide à ton workout du jour


2. Problème auquel l’application répond

L’application répond au problème suivant : Il répond au personne qui on tendance à noter leurs entrainements dans note et qui se perde dans ceux-ci


3. Logiciels requis

Pour installer et exécuter le projet, les logiciels suivants sont nécessaires :

AMPPS
Apache
PHP — version 8.2.33
MySQL — version 
Navigateur Web — Chrome, Firefox, Edge, etc.

AMPPS permet notamment de gérer l’environnement local comprenant Apache, PHP et MySQL.

Versions utilisées

Les versions importantes utilisées pour cette version du projet sont :

AMPPS	
Apache	
PHP	
MySQL	
4. Récupération du projet

Récupérer le projet depuis le dépôt Git :

git clone https://github.com/loicAubinL/SimpleWorkout_Site.git


Puis accéder au dossier du projet :

cd SimpleWorkout_Site


Le projet doit être placé dans le répertoire Web utilisé par AMPPS, par exemple :


C:\Program Files\Ampps\www\


Le dossier final doit par exemple être :

AMPPS/www/SimpleWorkout_Site/

5. Préparation de la base de données
Création de la base de données

Démarrer MySQL depuis AMPPS, puis créer la base de données nécessaire au projet.

Par exemple :

CREATE DATABASE SimpleWorkout_Site
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

Importation de la base de données

simpleWorkout_site.sql


l'importer dans la base de données créée précédemment.

Il est également possible d'utiliser phpMyAdmin fourni avec AMPPS pour créer la base de données et importer le fichier SQL.

Configuration de la connexion

Modifier le fichier de configuration du projet afin d'indiquer les informations de connexion à MySQL :

Hôte : localhost
Base de données : SimpleWorkout_Site
Utilisateur : root
Mot de passe : mysql
Port : [port, généralement 3306]

6. Démarrage d'Apache et de MySQL

Ouvrir AMPPS.

Dans le panneau de contrôle :

Démarrer Apache.
Démarrer MySQL.
Vérifier que les deux services sont actifs.

Apache sert les fichiers PHP de l'application tandis que MySQL fournit la base de données utilisée par celle-ci.

7. Configuration Apache

Le fichier de configuration Apache à modifier est :

C:\Program Files\Ampps\apache\conf\extra


Dans AMPPS, il s'agit généralement du fichier alias.conf situé dans le répertoire de configuration d'Apache.

Ajouter ou adapter le bloc Alias suivant :

Alias /projet "C:/Projets/SimpleWorkout_Site"

<Directory "C:/Projets/SimpleWorkout_Site">
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

Si le projet est mis dans C:/Projets/SimpleWorkout_Site


Important : Apache doit être redémarré après toute modification du fichier de configuration afin que les changements soient pris en compte.

8. Accès à l'application

Une fois Apache et MySQL démarrés, ouvrir un navigateur et accéder à :

http://localhost/Projet/



9. Installation, compilation et démarrage

Compilation PHP

Aucune compilation nécessaire.

Cette version du projet utilise PHP comme langage interprété. Les fichiers PHP sont exécutés directement par Apache avec le module PHP configuré dans AMPPS.

10. Résumé du démarrage

Pour démarrer rapidement l'application :

Installer AMPPS avec les versions requises de PHP, Apache et MySQL.
Récupérer le projet dans le répertoire www d'AMPPS.
Créer la base de données.
Importer le fichier SQL fourni, s'il y en a un.
Configurer les informations de connexion à MySQL.
Modifier la configuration Apache avec l'Alias et le bloc <Directory> appropriés.
Redémarrer Apache après la modification de sa configuration.
Démarrer Apache et MySQL dans AMPPS.
Ouvrir :
http://localhost/Projet/


L'application est alors accessible localement.