<?php
ob_start();
?>
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
            <li>
                <?php echo htmlspecialchars($exercice['name'], ENT_QUOTES, 'UTF-8'); ?>
                — <?php echo htmlspecialchars((string) $exercice['target_sets'], ENT_QUOTES, 'UTF-8'); ?> séries
                × <?php echo htmlspecialchars((string) $exercice['target_reps'], ENT_QUOTES, 'UTF-8'); ?> reps
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