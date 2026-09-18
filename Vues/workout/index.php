<?php
ob_start();
?>
<h1>Mes Workouts</h1>

<?php if (!empty($workouts)): ?>
    <ul>
        <?php foreach ($workouts as $workout): ?>
            <li>
                <a href="index.php?action=workout-detail&id=<?php echo htmlspecialchars($workout['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($workout['name'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Tu n'as pas encore de workout.</p>
<?php endif; ?>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../gabarit.php';
?>