<?php if (!empty($loggedSets)): ?>
    <ul>
        <?php foreach ($loggedSets as $set): ?>
            <li class="serie">
                <span class="serie-info">
                    <span class="serie-badge">Série <?= htmlspecialchars((string) $set['set_number'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?= htmlspecialchars((string) $set['weight'], ENT_QUOTES, 'UTF-8') ?> kg
                    × <?= htmlspecialchars((string) $set['reps'], ENT_QUOTES, 'UTF-8') ?> reps
                </span>
                <a class="bouton-danger" href="logged-set-supprimer/<?= htmlspecialchars((string) $set['id'], ENT_QUOTES, 'UTF-8') ?>">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="etat-vide">Aucune série enregistrée pour cet exercice.</p>
<?php endif; ?>