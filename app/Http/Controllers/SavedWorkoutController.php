<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SavedWorkoutController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Un workout redevenu privé par son auteur disparaît de la liste des autres.
        $splits = $user->savedWorkouts()
            ->where(fn ($q) => $q->where('is_public', true)->orWhere('workout_splits.user_id', $user->id))
            ->with('user:id,name')
            ->withCount(['workoutDays', 'exercises'])
            ->orderByPivot('created_at', 'desc')
            ->paginate(12);

        return view('saved.index', compact('splits'));
    }

    public function store(Request $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        Gate::authorize('save', $workoutSplit);

        $request->user()->savedWorkouts()->syncWithoutDetaching([$workoutSplit->id]);

        return back()->with('status', __('interface.saved_added', ['name' => $workoutSplit->name]));
    }

    public function destroy(Request $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        $request->user()->savedWorkouts()->detach($workoutSplit->id);

        return back()->with('status', __('interface.saved_removed', ['name' => $workoutSplit->name]));
    }
}
