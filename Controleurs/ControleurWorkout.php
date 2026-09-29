<?php

declare(strict_types=1);

require_once __DIR__ . '/Controleur.php';
require_once __DIR__ . '/../Modeles/WorkoutModele.php';
require_once __DIR__ . '/../Modeles/ExerciceModele.php';

class ControleurWorkout extends Controleur
{
    public function __construct(
        Vue $vue,
        private WorkoutModele $workoutModele,
        private ExerciceModele $exerciceModele
    ) {
        parent::__construct($vue);
    }

    public function liste(): void
    {
        $this->vue->rendre('workout/liste', [
            'titrePage' => 'Mes Workouts',
            'workouts' => $this->workoutModele->obtenirWorkouts(self::USER_ID_TEMP),
        ]);
    }

    public function detail(string $workoutId): void
    {
        $workout = $this->workoutModele->obtenirWorkoutDetail($workoutId);

        if ($workout === null) {
            $this->erreur(404, 'Workout non trouvé', "Le workout demandé n'existe pas.");
            return;
        }

        $this->vue->rendre('workout/detail', [
            'titrePage' => $workout['name'],
            'workout' => $workout,
            'jours' => $this->workoutModele->obtenirJoursDuWorkout($workoutId),
        ]);
    }

    public function jour(string $jourId): void
    {
        $jour = $this->workoutModele->obtenirDayDetail($jourId);

        if ($jour === null) {
            $this->erreur(404, 'Jour non trouvé', "Le jour demandé n'existe pas.");
            return;
        }

        $this->vue->rendre('jour/detail', [
            'titrePage' => $jour['name'],
            'jour' => $jour,
            'exercices' => $this->exerciceModele->obtenirExercicesDuJour($jourId),
        ]);
    }
}