<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\DeplaceElements;
use App\Http\Requests\EnregistrerExerciceRequest;
use App\Models\Exercise;
use App\Models\WorkoutDay;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ExerciseController extends Controller
{
    use DeplaceElements;

    public function create(WorkoutDay $day): View
    {
        Gate::authorize('update', $day->workoutSplit);

        return view('exercises.create', ['jour' => $day, 'exercice' => null]);
    }

    public function store(EnregistrerExerciceRequest $request, WorkoutDay $day): RedirectResponse
    {
        Gate::authorize('update', $day->workoutSplit);

        $day->exercises()->create([
            ...$request->validated(),
            'position' => ($day->exercises()->max('position') ?? -1) + 1,
        ]);

        // Un jour qui reçoit un exercice n'est plus un jour de repos.
        if ($day->is_rest_day) {
            $day->update(['is_rest_day' => false]);
        }

        return $this->retourAuJour($day)->with('status', __('interface.exercise_created'));
    }

    public function edit(Exercise $exercise): View
    {
        Gate::authorize('update', $exercise->workoutDay->workoutSplit);

        return view('exercises.edit', ['jour' => $exercise->workoutDay, 'exercice' => $exercise]);
    }

    public function update(EnregistrerExerciceRequest $request, Exercise $exercise): RedirectResponse
    {
        Gate::authorize('update', $exercise->workoutDay->workoutSplit);

        $exercise->update($request->validated());

        return $this->retourAuJour($exercise->workoutDay)->with('status', __('interface.exercise_updated'));
    }

    public function destroy(Exercise $exercise): RedirectResponse
    {
        $jour = $exercise->workoutDay;
        Gate::authorize('update', $jour->workoutSplit);

        $exercise->delete();

        return $this->retourAuJour($jour)->with('status', __('interface.exercise_deleted'));
    }

    public function move(Request $request, Exercise $exercise): RedirectResponse
    {
        $jour = $exercise->workoutDay;
        Gate::authorize('update', $jour->workoutSplit);

        $direction = $request->validate(['direction' => ['required', Rule::in(['up', 'down'])]])['direction'];
        $this->deplacer($jour->exercises()->get(), $exercise, $direction);

        return $this->retourAuJour($jour);
    }

    private function retourAuJour(WorkoutDay $jour): RedirectResponse
    {
        return redirect()->to(route('workout-splits.show', $jour->workout_split_id) . '#jour-' . $jour->id);
    }
}
