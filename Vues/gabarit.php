<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titrePage ?? 'Mon Application Fitness', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php?action=accueil">Accueil</a>
            <a href="index.php?action=exercice">Mes exercices</a>
            <a href="index.php?action=recits">Récits</a>
        </nav>
    </header>

    <main>
        <?= $contenu ?? '' ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Mon Application Workout</p>
    </footer>
</body>
</html>