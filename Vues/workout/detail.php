<h1><?= htmlspecialchars($workout['name'], ENT_QUOTES, 'UTF-8') ?></h1>

<?php if (!empty($jours)): ?>
    <ul>
        <?php foreach ($jours as $jour): ?>
            <li>
                <a href="jour/<?= htmlspecialchars((string) $jour['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($jour['name'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="etat-vide">Tu n'as pas encore de jour dans ce workout.</p>
<?php endif; ?>