<?php
ob_start();
?>
<h1>Bienvenue sur ton application de musculation</h1>
<p>Suis tes entraînements, organise tes exercices et progresse au quotidien.</p>


<?php
$contenu = ob_get_clean();

require __DIR__ . '/gabarit.php';