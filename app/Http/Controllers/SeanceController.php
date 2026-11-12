<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\LoggedSet;
use App\Models\WorkoutDay;
use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SeanceController extends Controller
{
    /**
     * Séance du jour : le jour courant du cycle, ou un autre jour choisi (?jour=ID).
     */
    public function show(Request $request, WorkoutSplit $workoutSplit): View
    {
        Gate::authorize('train', $workoutSplit);

        $workoutSplit->load('workoutDays.exercises');

        $choix = $request->validate([
            'jour' => ['nullable', 'integer', Rule::in($workoutSplit->workoutDays->pluck('id'))],
        ])['jour'] ?? null;

        $jour = $choix
            ? $workoutSplit->workoutDays->firstWhere('id', (int) $choix)
            : $workoutSplit->currentDay();

        abort_if($jour === null, 404);

        $user = $request->user();
        $idsExercices = $jour->exercises->pluck('id');

        // Séries enregistrées aujourd'hui, par exercice.
        $seriesDuJour = $user->loggedSets()
            ->whereIn('exercise_id', $idsExercices)
            ->whereDate('logged_at', today())
            ->orderBy('set_number')
            ->get()
            ->groupBy('exercise_id');

        // Dernière séance précédente de chaque exercice (pour afficher « la dernière fois »).
        $precedentes = $user->loggedSets()
            ->whereIn('exercise_id', $idsExercices)
            ->whereDate('logged_at', '<', today())
            ->orderByDesc('logged_at')
            ->get()
            ->groupBy('exercise_id')
            ->map(function ($series) {
                $date = $series->first()->logged_at->toDateString();

                return $series->filter(fn (LoggedSet $s) => $s->logged_at->toDateString() === $date)
                    ->sortBy('set_number')
                    ->values();
            });

        return view('seance.show', [
            'split' => $workoutSplit,
            'jour' => $jour,
            'seriesDuJour' => $seriesDuJour,
            'precedentes' => $precedentes,
        ]);
    }

    public function storeSet(Request $request, Exercise $exercise): RedirectResponse
    {
        $split = $exercise->workoutDay->workoutSplit;
        Gate::authorize('train', $split);

        $donnees = $request->validate([
            'weight' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'reps' => ['required', 'integer', 'min:1', 'max:200'],
        ]);

        $user = $request->user();
        $numero = $user->loggedSets()
            ->where('exercise_id', $exercise->id)
            ->whereDate('logged_at', today())
            ->max('set_number') ?? 0;

        $user->loggedSets()->create([
            'exercise_id' => $exercise->id,
            'set_number' => $numero + 1,
            'weight' => $donnees['weight'] ?? null,
            'reps' => $donnees['reps'],
            'logged_at' => now(),
        ]);

        return $this->retourSeance($split, $exercise->workoutDay, $exercise)
            ->with('status', __('interface.set_logged'));
    }

    public function destroySet(Request $request, LoggedSet $loggedSet): RedirectResponse
    {
        abort_unless($loggedSet->user_id === $request->user()->id, 403);

        $exercice = $loggedSet->exercise;
        $loggedSet->delete();

        return $this->retourSeance($exercice->workoutDay->workoutSplit, $exercice->workoutDay, $exercice)
            ->with('status', __('interface.set_deleted'));
    }

    /**
     * Terminer la séance : le cycle passe au jour qui suit celui qui vient d'être fait.
     */
    public function finish(Request $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        Gate::authorize('train', $workoutSplit);

        $jours = $workoutSplit->workoutDays()->pluck('id');
        $jourFait = (int) $request->validate([
            'jour' => ['required', 'integer', Rule::in($jours)],
        ])['jour'];

        $workoutSplit->update([
            'current_position' => ($jours->search($jourFait) + 1) % $jours->count(),
        ]);

        return redirect()->route('history.index')->with('status', __('interface.session_finished'));
    }

    private function retourSeance(WorkoutSplit $split, WorkoutDay $jour, Exercise $exercice): RedirectResponse
    {
        return redirect()->to(
            route('session.show', ['workoutSplit' => $split, 'jour' => $jour->id]) . '#exercice-' . $exercice->id
        );
    }
}
