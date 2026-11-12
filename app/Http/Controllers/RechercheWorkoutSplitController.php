<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSplit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RechercheWorkoutSplitController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:50'],
        ]);
        $terme = $data['q'];

        $user = $request->user();

        $splits = $user->workoutSplits()
            ->where(function ($requete) use ($terme) {
                $motif = '%' . $terme . '%';
                $requete->where('name', 'like', $motif)
                    ->orWhereHas('workoutDays', fn($jours) => $jours->where('name', 'like', $motif))
                    ->orWhereHas('exercises', fn($exercices) => $exercices->where('exercises.name', 'like', $motif));
            })
            ->withCount('workoutDays')
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name']);

        return response()->json([
            'data' => $splits->map(fn(WorkoutSplit $split) => [
                'id' => $split->id,
                'label' => $split->name,
                'details' => trans_choice('interface.days_count', $split->workout_days_count),
                'url' => route('workout-splits.show', $split),
            ]),
        ]);
    }
}
