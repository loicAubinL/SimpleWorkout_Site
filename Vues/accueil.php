<?php
ob_start();
?>

<?php if (!empty($_SESSION['erreur_formulaire'])): ?>
    <p role="alert" class="message-erreur">
        <?php echo htmlspecialchars($_SESSION['erreur_formulaire'], ENT_QUOTES, 'UTF-8'); ?>
    </p>
    <?php unset($_SESSION['erreur_formulaire']); ?>
<?php endif; ?>


<h1>Bienvenue sur ton application de musculation</h1>
<p>Suis tes entraînements, organise tes exercices et progresse au quotidien.</p>

<?php
$aUnSplitActif = isset($split, $jour) && $split !== null && $jour !== null;
?>

<h1>
    <?php if ($aUnSplitActif): ?>
        Workout du jour : <?php echo htmlspecialchars($jour['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
    <?php else: ?>
        Aucun workout actif
    <?php endif; ?>
</h1>

<?php if ($aUnSplitActif && !empty($exercices)): ?>
    <ul>
        <?php foreach ($exercices as $exercice): ?>
            <li class="carte-exercice">
                <?php echo htmlspecialchars($exercice['name'], ENT_QUOTES, 'UTF-8'); ?>
                — <?php echo htmlspecialchars((string) $exercice['target_sets'], ENT_QUOTES, 'UTF-8'); ?> séries
                × <?php echo htmlspecialchars((string) $exercice['target_reps'], ENT_QUOTES, 'UTF-8'); ?> reps

                <form method="post" action="index.php?action=logged-set-ajouter">
                    <input type="hidden" name="exercise_id" value="<?php echo htmlspecialchars($exercice['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="number" name="set_number" placeholder="Série #" required>
                    <input type="number" name="weight" step="0.5" placeholder="Poids" required>
                    <input type="number" name="reps" placeholder="Reps" required>
                    <button type="submit">Confirmer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php elseif ($aUnSplitActif): ?>
    <p>Aucun exercice pour ce jour.</p>
<?php else: ?>
    <p>Tu n'as pas encore de split actif. Crée ton premier workout !</p>
<?php endif; ?>



<?php
$contenu = ob_get_clean();
$titrePage = $aUnSplitActif ? "Workout - " . ($jour['name'] ?? '') : "Accueil Workout";
require __DIR__ . '/gabarit.php';
