<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titrePage ?? 'Mon Application Fitness', ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Mes workouts</a>
            <a href="recits.php">Récits</a>
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