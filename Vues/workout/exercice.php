<?php
ob_start();
?>
<h1><?php echo htmlspecialchars($titrePage, ENT_QUOTES, 'UTF-8'); ?></h1>

<?php if (!empty($exercices)): ?>
    <ul>
        <?php foreach ($exercices as $exercice): ?>
            <li>
                <span class="exercice-nom"><?php echo htmlspecialchars($exercice['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="exercice-details">
                    <?php echo htmlspecialchars((string) $exercice['target_sets'], ENT_QUOTES, 'UTF-8'); ?> séries
                    × <?php echo htmlspecialchars($exercice['target_reps'], ENT_QUOTES, 'UTF-8'); ?> reps
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun exercice pour ce jour.</p>
<?php endif; ?>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../gabarit.php';
?>