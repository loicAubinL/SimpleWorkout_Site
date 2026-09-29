<?php

declare(strict_types=1);

class ControleurLoggedSet
{
    private const USER_ID_TEMP = 'a1111111-1111-1111-1111-111111111111';

    private $exercices;
    private $loggedSets;
    private $vue;
    private $erreurs;

    public function __construct(ExerciceModele $exercices, LoggedSetModele $loggedSets, Vue $vue, ControleurErreur $erreurs)
    {
        $this->exercices = $exercices;
        $this->loggedSets = $loggedSets;
        $this->vue = $vue;
        $this->erreurs = $erreurs;
    }

    public function liste(string $exerciceId): void
    {
        $exercice = $this->exercices->obtenirExerciceParId($exerciceId);

        if ($exercice === null) {
            $this->erreurs->afficher('Exercice introuvable.', 404);
            return;
        }

        $this->vue->afficher('exercice/detail', [
            'exercice' => $exercice,
            'loggedSets' => $this->loggedSets->obtenirLoggedSetsParExercice($exerciceId),
        ], $exercice['name']);
    }

    public function ajouter(array $donnees): void
    {
        $exerciceId = trim((string) ($donnees['exercise_id'] ?? ''));
        $setNumber = trim((string) ($donnees['set_number'] ?? ''));
        $weight = trim((string) ($donnees['weight'] ?? ''));
        $reps = trim((string) ($donnees['reps'] ?? ''));

        if ($exerciceId === '' || $setNumber === '' || $weight === '' || $reps === '') {
            $_SESSION['erreur_formulaire'] = 'Tous les champs sont requis.';
            rediriger();
        }

        if ($this->exercices->obtenirExerciceParId($exerciceId) === null) {
            $this->erreurs->afficher('Exercice introuvable.', 404);
            return;
        }

        $this->loggedSets->ajouterLoggedSet($exerciceId, self::USER_ID_TEMP, (int) $setNumber, (float) $weight, (int) $reps);
        rediriger();
    }

    public function confirmerSuppression(string $id): void
    {
        $set = $this->loggedSets->obtenirLoggedSetParId($id);

        if ($set === null) {
            $this->erreurs->afficher("Cette série n'existe pas ou a déjà été supprimée.", 404);
            return;
        }

        $this->vue->afficher('logged-set/confirmer-suppression', ['set' => $set], 'Supprimer une série');
    }

    public function supprimer(string $id): void
    {
        $set = $this->loggedSets->obtenirLoggedSetParId($id);

        if ($set === null) {
            $this->erreurs->afficher("Cette série n'existe pas ou a déjà été supprimée.", 404);
            return;
        }

        $this->loggedSets->supprimerLoggedSet($id);
        rediriger('exercice/' . urlencode((string) $set['exercise_id']));
    }
}