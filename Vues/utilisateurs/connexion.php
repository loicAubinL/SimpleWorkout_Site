<section class="carte-auth">
    <h1>Connexion</h1>
    <p class="sous-titre">Content de te revoir ! Connecte-toi pour suivre tes entraînements.</p>

    <?php if ($erreur !== null): ?>
        <p role="alert" class="message-erreur"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form action="authentifier" method="post" class="formulaire">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Courriel</label>
        <input id="email" name="email" type="email" autocomplete="username" required
            placeholder="toi@exemple.com"
            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

        <label for="mot_de_passe">Mot de passe</label>
        <input id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="current-password" required>

        <button type="submit" class="bouton-plein">Se connecter</button>
    </form>

    <p class="lien-bas">Pas encore de compte ? <a href="inscription">Créer un compte</a></p>
</section>