<h1><?= htmlspecialchars($jour['name'], ENT_QUOTES, 'UTF-8') ?></h1>

<?php if (!empty($exercices)): ?>
    <ul>
        <?php foreach ($exercices as $exercice): ?>
            <li>
                <a href="exercice/<?= htmlspecialchars((string) $exercice['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <span class="exercice-nom"><?= htmlspecialchars($exercice['name'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="exercice-details">
                        <?= htmlspecialchars((string) $exercice['target_sets'], ENT_QUOTES, 'UTF-8') ?> séries
                        × <?= htmlspecialchars((string) $exercice['target_reps'], ENT_QUOTES, 'UTF-8') ?> reps
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="etat-vide">Aucun exercice pour ce jour.</p>
<?php endif; ?>