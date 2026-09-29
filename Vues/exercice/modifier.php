<h1>Modifier l'exercice</h1>

<?php if ($erreurs !== []): ?>
    <p role="alert" class="message-erreur">Le formulaire contient des erreurs.</p>
<?php endif; ?>

<form method="post" action="exercice-enregistrer" class="formulaire" novalidate>
    <input type="hidden" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">

    <?php
    $champs = [
        'name' => ['Nom', 'text'],
        'target_sets' => ['Séries', 'number'],
        'target_reps' => ['Répétitions', 'text'],
    ];
    ?>

    <?php foreach ($champs as $nom => [$libelle, $type]): ?>
        <label for="<?= $nom ?>"><?= $libelle ?></label>
        <input id="<?= $nom ?>" name="<?= $nom ?>" type="<?= $type ?>"
            value="<?= htmlspecialchars($valeurs[$nom], ENT_QUOTES, 'UTF-8') ?>"
            <?php if (isset($erreurs[$nom])): ?>aria-invalid="true" aria-describedby="erreur-<?= $nom ?>" <?php endif; ?>>
        <?php if (isset($erreurs[$nom])): ?>
            <p id="erreur-<?= $nom ?>" class="message-erreur"><?= htmlspecialchars($erreurs[$nom], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    <?php endforeach; ?>

    <label for="notes">Notes</label>
    <textarea id="notes" name="notes"
        <?php if (isset($erreurs['notes'])): ?>aria-invalid="true" aria-describedby="erreur-notes" <?php endif; ?>><?= htmlspecialchars($valeurs['notes'], ENT_QUOTES, 'UTF-8') ?></textarea>
    <?php if (isset($erreurs['notes'])): ?>
        <p id="erreur-notes" class="message-erreur"><?= htmlspecialchars($erreurs['notes'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <button type="submit">Enregistrer</button>
    <div class="actions">
        <button type="submit">Enregistrer</button>
        <a href="exercice/<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">Annuler</a>
    </div>
</form>