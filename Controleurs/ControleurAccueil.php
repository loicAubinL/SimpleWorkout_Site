<?php

declare(strict_types=1);

class ControleurAccueil
{
    private const USER_ID_TEMP = 'a1111111-1111-1111-1111-111111111111';

    private $workouts;
    private $exercices;
    private $vue;

    public function __construct(WorkoutModele $workouts, ExerciceModele $exercices, Vue $vue)
    {
        $this->workouts = $workouts;
        $this->exercices = $exercices;
        $this->vue = $vue;
    }

    public function index(): void
    {
        $jour = null;
        $exercices = [];
        $split = $this->workouts->obtenirSplitActif(self::USER_ID_TEMP);

        if ($split !== null) {
            $jour = $this->workouts->obtenirJourActif((string) $split['id'], (int) $split['current_position']);

            if ($jour !== null) {
                $exercices = $this->exercices->obtenirExercicesDuJour((string) $jour['id']);
            }
        }

        $titrePage = $jour !== null ? 'Workout - ' . $jour['name'] : 'Accueil Workout';
$this->vue->afficher('accueil', compact('jour', 'exercices'), $titrePage);
    }
}