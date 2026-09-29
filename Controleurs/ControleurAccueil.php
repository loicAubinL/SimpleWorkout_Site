<?php

declare(strict_types=1);

require_once __DIR__ . '/Controleur.php';
require_once __DIR__ . '/../Modeles/WorkoutModele.php';
require_once __DIR__ . '/../Modeles/ExerciceModele.php';

class ControleurAccueil extends Controleur
{
    public function __construct(
        Vue $vue,
        private WorkoutModele $workoutModele,
        private ExerciceModele $exerciceModele
    ) {
        parent::__construct($vue);
    }

    public function index(): void
    {
        $jour = null;
        $exercices = [];
        $split = $this->workoutModele->obtenirSplitActif(self::USER_ID_TEMP);

        if ($split !== null) {
            $jour = $this->workoutModele->obtenirJourActif((string) $split['id'], (int) $split['current_position']);

            if ($jour !== null) {
                $exercices = $this->exerciceModele->obtenirExercicesDuJour((string) $jour['id']);
            }
        }

        $this->vue->rendre('accueil', [
            'titrePage' => 'Accueil',
            'jour' => $jour,
            'exercices' => $exercices,
        ]);
    }
}