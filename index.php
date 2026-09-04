<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
$nomProjet = 'SimpleWorkout_Site';
$auteur = 'Loic Aubin-lemery';
$versionPhp = PHP_VERSION;
?>



<!DOCTYPE html>
<html>
<body>
    <h1><?php echo $nomProjet; ?></h1>
    <p>Par <?php echo $auteur; ?> — PHP <?php echo $versionPhp; ?></p>
</body>
</html>