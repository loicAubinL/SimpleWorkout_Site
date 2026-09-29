<?php

declare(strict_types=1);

class ControleurWorkout
{
    private const USER_ID_TEMP = 'a1111111-1111-1111-1111-111111111111';

    private $workouts;
    private $exercices;
    private $vue;
    private $erreurs;

    public function __construct(WorkoutModele $workouts, ExerciceModele $exercices, Vue $vue, ControleurErreur $erreurs)
    {
        $this->workouts = $workouts;
        $this->exercices = $exercices;
        $this->vue = $vue;
        $this->erreurs = $erreurs;
    }

    public function liste(): void
    {
        $this->vue->afficher('workout/liste', [
            'workouts' => $this->workouts->obtenirWorkouts(self::USER_ID_TEMP),
        ], 'Mes Workouts');
    }

    public function detail(string $id): void
    {
        $workout = $this->workouts->obtenirWorkoutDetail($id);

        if ($workout === null) {
            $this->erreurs->afficher('Workout introuvable.', 404);
            return;
        }

        $this->vue->afficher('workout/detail', [
            'workout' => $workout,
            'jours' => $this->workouts->obtenirJoursDuWorkout($id),
        ], $workout['name']);
    }

    public function jour(string $id): void
    {
        $jour = $this->workouts->obtenirDayDetail($id);

        if ($jour === null) {
            $this->erreurs->afficher('Jour introuvable.', 404);
            return;
        }

        $this->vue->afficher('jour/detail', [
            'jour' => $jour,
            'exercices' => $this->exercices->obtenirExercicesDuJour($id),
        ], $jour['name']);
    }
}