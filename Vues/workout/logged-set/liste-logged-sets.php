<?php if (!empty($loggedSets)): ?>
    <ul>
        <?php foreach ($loggedSets as $set): ?>
            <li class="serie">
                <span class="serie-info">
                    <span class="serie-badge">Série <?php echo htmlspecialchars((string) $set['set_number'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php echo htmlspecialchars((string) $set['weight'], ENT_QUOTES, 'UTF-8'); ?> kg
                    × <?php echo htmlspecialchars((string) $set['reps'], ENT_QUOTES, 'UTF-8'); ?> reps
                </span>
                <a class="bouton-danger" href="index.php?action=logged-set-supprimer&id=<?php echo htmlspecialchars($set['id'], ENT_QUOTES, 'UTF-8'); ?>">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="etat-vide">Aucune série enregistrée pour cet exercice.</p>
<?php endif; ?>