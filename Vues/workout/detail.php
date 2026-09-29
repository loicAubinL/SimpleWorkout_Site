<?php
ob_start();
?>
<h1>Mes Jours</h1>

<?php if (!empty($jours)): ?>
    <ul>
        <?php foreach ($jours as $jour): ?>
            <li>
                <a href="index.php?action=day-detail&id=<?php echo htmlspecialchars($jour['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($jour['name'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Tu n'as pas encore de jour dans ton workout</p>
<?php endif; ?>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../gabarit.php';
?>