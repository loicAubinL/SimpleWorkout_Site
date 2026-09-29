<h1>Mes Workouts</h1>

<?php if (!empty($workouts)): ?>
    <ul>
        <?php foreach ($workouts as $workout): ?>
            <li>
                <a href="workout/<?= htmlspecialchars((string) $workout['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($workout['name'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="etat-vide">Tu n'as pas encore de workout.</p>
<?php endif; ?>