<?php
ob_start();
?>
<h1><?php echo htmlspecialchars($titrePage, ENT_QUOTES, 'UTF-8'); ?></h1>

<?php require __DIR__ . '/liste-logged-sets.php'; ?>

<?php
$contenu = ob_get_clean();
require __DIR__ . '/../gabarit.php';
?>