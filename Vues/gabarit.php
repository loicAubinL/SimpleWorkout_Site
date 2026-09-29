<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= htmlspecialchars(urlBase(), ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($titrePage ?: 'Mon Application Fitness', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="./">Accueil</a>
            <a href="workouts">Mes Workouts</a>
            <a href="recits">Récits</a>
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