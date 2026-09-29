<h1><?= htmlspecialchars($exercice['name'], ENT_QUOTES, 'UTF-8') ?></h1>
<p><a class="bouton" href="exercice-modifier/<?= htmlspecialchars((string) $exercice['id'], ENT_QUOTES, 'UTF-8') ?>">Modifier cet exercice</a></p>
<?php require __DIR__ . '/../logged-set/liste.php'; ?>