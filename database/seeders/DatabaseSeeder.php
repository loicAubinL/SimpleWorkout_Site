<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::factory()->create([
            'name' => 'Utilisateur démo',
            'email' => 'demo@example.test',
            'role' => User::ROLE_MEMBER,
        ]);

        User::factory()->create([
            'name' => 'Responsable',
            'email' => 'responsable@example.test',
            'role' => User::ROLE_MANAGER,
        ]);

        $alex = User::factory()->create([
            'name' => 'Alex Tremblay',
            'email' => 'alex@example.test',
            'role' => User::ROLE_MEMBER,
        ]);

        // Exercice : [nom, séries, reps, charge, repos (s)]
        $ppl = $this->creerSplit($demo, [
            'name' => 'Push Pull Legs',
            'description' => 'Split classique sur trois jours, suivi d\'un jour de repos.',
            'is_active' => true,
            'is_public' => true,
        ], [
            'Push' => [
                ['Développé couché', 4, '6-8', 70, 150],
                ['Développé militaire', 3, '8-10', 40, 120],
                ['Écarté poulie', 3, '12-15', 15, 60],
                ['Extension triceps corde', 3, '12', 20, 60],
            ],
            'Pull' => [
                ['Tractions', 4, '6-8', null, 150],
                ['Rowing barre', 3, '8-10', 60, 120],
                ['Face pull', 3, '15', 15, 60],
                ['Curl haltères', 3, '10-12', 12, 60],
            ],
            'Legs' => [
                ['Squat', 4, '5', 100, 180],
                ['Soulevé de terre roumain', 3, '8', 80, 150],
                ['Presse à cuisses', 3, '10-12', 160, 120],
                ['Mollets debout', 4, '12-15', 60, 60],
            ],
            'Repos' => null,
        ]);

        $this->creerSplit($demo, [
            'name' => 'Full Body débutant',
            'description' => 'Deux séances complètes par semaine.',
        ], [
            'Séance A' => [
                ['Squat goblet', 3, '10', 20, 90],
                ['Pompes', 3, '10-15', null, 90],
                ['Rowing haltère', 3, '10', 18, 90],
            ],
            'Séance B' => [
                ['Fentes', 3, '10', 12, 90],
                ['Développé haltères', 3, '10', 18, 90],
                ['Tirage vertical', 3, '10', 45, 90],
            ],
        ]);

        $upperLower = $this->creerSplit($alex, [
            'name' => 'Upper / Lower',
            'description' => 'Quatre séances par semaine, haut et bas du corps en alternance.',
            'is_active' => true,
            'is_public' => true,
        ], [
            'Upper 1' => [
                ['Développé couché', 4, '5', 80, 180],
                ['Rowing barre', 4, '6-8', 70, 150],
                ['Développé militaire', 3, '8', 45, 120],
            ],
            'Lower 1' => [
                ['Squat', 4, '5', 110, 180],
                ['Leg curl', 3, '10-12', 40, 90],
            ],
            'Upper 2' => [
                ['Développé incliné haltères', 3, '8-10', 28, 120],
                ['Tractions lestées', 3, '6', 10, 150],
            ],
            'Lower 2' => [
                ['Soulevé de terre', 3, '5', 140, 180],
                ['Fentes bulgares', 3, '8-10', 20, 90],
            ],
        ]);

        $this->creerSplit($alex, [
            'name' => 'Bras express',
            'description' => 'Une séance courte de 30 minutes pour les bras.',
            'is_public' => true,
        ], [
            'Bras' => [
                ['Curl barre EZ', 3, '10', 30, 60],
                ['Dips', 3, '8-12', null, 90],
                ['Curl marteau', 3, '12', 14, 60],
            ],
        ]);

        $this->creerSplit($alex, ['name' => 'Programme perso d\'Alex'], [
            'Cardio' => [['Vélo', 1, '30 min', null, null]],
        ]);

        $demo->savedWorkouts()->attach($upperLower);

        $this->creerHistorique($demo, $ppl);
    }

    /**
     * @param  array<string, array<int, array{0:string,1:int,2:string,3:float|int|null,4:int|null}>|null>  $jours
     */
    private function creerSplit(User $user, array $attributs, array $jours): WorkoutSplit
    {
        $split = $user->workoutSplits()->create($attributs);

        $position = 0;
        foreach ($jours as $nom => $exercices) {
            $jour = $split->workoutDays()->create([
                'name' => $nom,
                'user_id' => $user->id,
                'position' => $position++,
                'is_rest_day' => $exercices === null,
            ]);

            foreach ($exercices ?? [] as $i => [$nomExercice, $series, $reps, $charge, $repos]) {
                $jour->exercises()->create([
                    'name' => $nomExercice,
                    'target_sets' => $series,
                    'target_reps' => $reps,
                    'weight' => $charge,
                    'rest_seconds' => $repos,
                    'position' => $i,
                ]);
            }
        }

        return $split;
    }

    /**
     * Six semaines de séances passées, avec une charge qui augmente doucement.
     */
    private function creerHistorique(User $user, WorkoutSplit $split): void
    {
        $jours = $split->workoutDays()->with('exercises')->get()->reject->is_rest_day->values();
        $date = Carbon::today()->subWeeks(6)->setTime(18, 0);
        $seance = 0;

        while ($date->lt(Carbon::today())) {
            $jour = $jours[$seance % $jours->count()];
            $semaine = intdiv($seance, $jours->count());

            foreach ($jour->exercises as $exercice) {
                for ($serie = 1; $serie <= $exercice->target_sets; $serie++) {
                    $user->loggedSets()->create([
                        'exercise_id' => $exercice->id,
                        'set_number' => $serie,
                        'weight' => $exercice->weight !== null ? $exercice->weight - 7.5 + 2.5 * $semaine : null,
                        'reps' => (int) $exercice->target_reps + ($serie === $exercice->target_sets ? -1 : 0),
                        'logged_at' => $date->copy()->addMinutes(3 * $serie),
                    ]);
                }
            }

            $seance++;
            $date->addDays($seance % 3 === 0 ? 2 : 1);
        }

        $split->update(['current_position' => $seance % $split->workoutDays()->count()]);
    }
}
