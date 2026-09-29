<section class="carte-auth">
    <h1>Créer un compte</h1>
    <p class="sous-titre">Quelques secondes pour commencer à suivre tes workouts.</p>

    <?php if ($erreurs !== []): ?>
        <p role="alert" class="message-erreur">Le formulaire contient des erreurs.</p>
    <?php endif; ?>

    <form action="creer-compte" method="post" class="formulaire" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="nom">Nom</label>
        <input id="nom" name="nom" type="text" autocomplete="name"
            value="<?= htmlspecialchars($valeurs['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            <?php if (isset($erreurs['nom'])): ?>aria-invalid="true" aria-describedby="erreur-nom" <?php endif; ?>>
        <?php if (isset($erreurs['nom'])): ?>
            <p id="erreur-nom" class="message-erreur"><?= htmlspecialchars($erreurs['nom'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <label for="email">Courriel (identifiant)</label>
        <input id="email" name="email" type="email" autocomplete="username" placeholder="toi@exemple.com"
            value="<?= htmlspecialchars($valeurs['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            <?php if (isset($erreurs['email'])): ?>aria-invalid="true" aria-describedby="erreur-email" <?php endif; ?>>
        <?php if (isset($erreurs['email'])): ?>
            <p id="erreur-email" class="message-erreur"><?= htmlspecialchars($erreurs['email'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <label for="mot_de_passe">Mot de passe</label>
        <input id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="new-password"
            aria-describedby="aide-mdp<?= isset($erreurs['mot_de_passe']) ? ' erreur-mot_de_passe' : '' ?>"
            <?php if (isset($erreurs['mot_de_passe'])): ?>aria-invalid="true" <?php endif; ?>>
        <p id="aide-mdp" class="aide">Au moins 8 caractères.</p>
        <?php if (isset($erreurs['mot_de_passe'])): ?>
            <p id="erreur-mot_de_passe" class="message-erreur"><?= htmlspecialchars($erreurs['mot_de_passe'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <label for="confirmation">Confirmer le mot de passe</label>
        <input id="confirmation" name="confirmation" type="password" autocomplete="new-password"
            <?php if (isset($erreurs['confirmation'])): ?>aria-invalid="true" aria-describedby="erreur-confirmation" <?php endif; ?>>
        <?php if (isset($erreurs['confirmation'])): ?>
            <p id="erreur-confirmation" class="message-erreur"><?= htmlspecialchars($erreurs['confirmation'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <button type="submit" class="bouton-plein">Créer mon compte</button>
    </form>

    <p class="lien-bas">Déjà un compte ? <a href="connexion">Se connecter</a></p>
</section>