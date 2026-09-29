<?php if (!empty($_SESSION['erreur_formulaire'])): ?>
    <p role="alert" class="message-erreur">
        <?= htmlspecialchars($_SESSION['erreur_formulaire'], ENT_QUOTES, 'UTF-8') ?>
    </p>
    <?php unset($_SESSION['erreur_formulaire']); ?>
<?php endif; ?>

<h1>Bienvenue sur ton application de musculation</h1>
<p>Suis tes entraînements, organise tes exercices et progresse au quotidien.</p>

<?php if ($jour !== null): ?>
    <h1>Workout du jour : <?= htmlspecialchars($jour['name'], ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($exercices)): ?>
        <ul>
            <?php foreach ($exercices as $exercice): ?>
                <li class="carte-exercice">
                    <?= htmlspecialchars($exercice['name'], ENT_QUOTES, 'UTF-8') ?>
                    — <?= htmlspecialchars((string) $exercice['target_sets'], ENT_QUOTES, 'UTF-8') ?> séries
                    × <?= htmlspecialchars((string) $exercice['target_reps'], ENT_QUOTES, 'UTF-8') ?> reps

                    <form method="post" action="logged-set-ajouter">
                        <input type="hidden" name="exercise_id" value="<?= htmlspecialchars((string) $exercice['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="number" name="set_number" placeholder="Série #" required>
                        <input type="number" name="weight" step="0.5" placeholder="Poids" required>
                        <input type="number" name="reps" placeholder="Reps" required>
                        <button type="submit">Confirmer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun exercice pour ce jour.</p>
    <?php endif; ?>
<?php else: ?>
    <h1>Aucun workout actif</h1>
    <p>Tu n'as pas encore de split actif. Crée ton premier workout !</p>
<?php endif; ?>