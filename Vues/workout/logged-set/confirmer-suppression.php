<?php
ob_start();
?>
<?php $id = $id ?? ''; ?>
<h1>Confirmer la suppression</h1>
<p>Supprimer cette série définitivement ?</p>

<form method="post" action="index.php?action=logged-set-supprimer-confirme">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(jetonCsrf(), ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit" class="bouton-danger">Confirmer la suppression</button>
</form>

<?php
$contenu = ob_get_clean();
$titrePage = 'Confirmer';
require __DIR__ . '/../gabarit.php';
?>