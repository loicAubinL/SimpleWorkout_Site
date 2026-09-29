<?php

declare(strict_types=1);

require_once __DIR__ . '/Controleur.php';
require_once __DIR__ . '/../Modeles/ExerciceModele.php';
require_once __DIR__ . '/../Modeles/LoggedSetModele.php';

class ControleurLoggedSet extends Controleur
{
    public function __construct(
        Vue $vue,
        private ExerciceModele $exerciceModele,
        private LoggedSetModele $loggedSetModele
    ) {
        parent::__construct($vue);
    }

    public function liste(string $exerciceId): void
    {
        $exercice = $this->exerciceModele->obtenirExerciceParId($exerciceId);

        if ($exercice === null) {
            $this->erreur(404, 'Exercice non trouvé', "L'exercice demandé n'existe pas.");
            return;
        }

        $this->vue->rendre('exercice/detail', [
            'titrePage' => $exercice['name'],
            'exercice' => $exercice,
            'loggedSets' => $this->loggedSetModele->obtenirLoggedSetsParExercice($exerciceId),
        ]);
    }

    public function ajouter(): void
    {
        $exerciceId = trim($_POST['exercise_id'] ?? '');
        $setNumber = trim($_POST['set_number'] ?? '');
        $weight = trim($_POST['weight'] ?? '');
        $reps = trim($_POST['reps'] ?? '');

        if ($exerciceId === '' || $setNumber === '' || $weight === '' || $reps === '') {
            $_SESSION['erreur_formulaire'] = 'Tous les champs sont requis.';
            $this->rediriger('index.php?action=accueil');
        }

        if ($this->exerciceModele->obtenirExerciceParId($exerciceId) === null) {
            $this->erreur(404, 'Exercice introuvable', "L'exercice concerné n'existe pas.");
            return;
        }

        $this->loggedSetModele->ajouterLoggedSet(
            $exerciceId,
            self::USER_ID_TEMP,
            (int) $setNumber,
            (float) $weight,
            (int) $reps
        );

        $this->rediriger('index.php?action=accueil');
    }

    public function confirmerSuppression(string $id): void
    {
        $set = $this->loggedSetModele->obtenirLoggedSetParId($id);

        if ($set === null) {
            $this->erreur(404, 'Série introuvable', "Cette série n'existe pas ou a déjà été supprimée.");
            return;
        }

        $this->vue->rendre('logged-set/confirmer-suppression', [
            'titrePage' => 'Supprimer une série',
            'set' => $set,
        ]);
    }

    public function supprimer(): void
    {
        $id = trim($_POST['id'] ?? '');

        if ($id === '') {
            $this->erreur(400, 'Identifiant manquant', 'Aucun identifiant fourni.');
            return;
        }

        $set = $this->loggedSetModele->obtenirLoggedSetParId($id);

        if ($set === null) {
            $this->erreur(404, 'Série introuvable', "Cette série n'existe pas ou a déjà été supprimée.");
            return;
        }

        $this->loggedSetModele->supprimerLoggedSet($id);
        $this->rediriger('index.php?action=exercice-detail&id=' . urlencode((string) $set['exercise_id']));
    }
}