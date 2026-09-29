<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= htmlspecialchars(urlBase(), ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($titrePage ?: 'Mon Application Fitness', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="css/style.css?v=2">
</head>

<body>
    <header>
        <nav>
            <a href="./">Accueil</a>
            <a href="workouts">Mes Workouts</a>
            <a href="recits">Récits</a>

            <?php if ($utilisateurCourant !== null): ?>
                <span class="nav-utilisateur"><?= htmlspecialchars($utilisateurCourant['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                <form action="deconnexion" method="post" class="nav-deconnexion">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit">Se déconnecter</button>
                </form>
            <?php else: ?>
                <a href="connexion">Connexion</a>
                <a href="inscription">Créer un compte</a>
            <?php endif; ?>
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