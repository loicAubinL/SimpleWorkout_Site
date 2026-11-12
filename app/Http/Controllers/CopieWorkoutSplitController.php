<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSplit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CopieWorkoutSplitController extends Controller
{
    /**
     * Copie un workout (jours et exercices compris) dans les workouts de l'utilisateur, pour le modifier.
     */
    public function __invoke(Request $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        Gate::authorize('copy', $workoutSplit);

        $copie = $workoutSplit->duplicateFor($request->user());

        return redirect()->route('workout-splits.show', $copie)
            ->with('status', __('interface.copied'));
    }
}
