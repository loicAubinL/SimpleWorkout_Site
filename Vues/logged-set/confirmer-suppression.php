<h1>Confirmer la suppression</h1>
<p>
    Supprimer la série <?= htmlspecialchars((string) $set['set_number'], ENT_QUOTES, 'UTF-8') ?>
    (<?= htmlspecialchars((string) $set['weight'], ENT_QUOTES, 'UTF-8') ?> kg
    × <?= htmlspecialchars((string) $set['reps'], ENT_QUOTES, 'UTF-8') ?> reps) définitivement ?
</p>

<form method="post" action="logged-set-supprimer-confirme">
    <input type="hidden" name="id" value="<?= htmlspecialchars((string) $set['id'], ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit" class="bouton-danger">Confirmer la suppression</button>
</form>

<a href="exercice/<?= htmlspecialchars((string) $set['exercise_id'], ENT_QUOTES, 'UTF-8') ?>">Annuler</a>