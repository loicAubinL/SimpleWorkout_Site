<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        $user = User::query()->orderBy('id')->firstOrFail();

        $splits = $user->workoutSplits()
            ->where(function ($requete) use ($terme) {
                $requete->whereHas('workoutDays', fn($jours) => $jours->where('name', 'like', '%' . $terme . '%'));
            })
            ->withCount('workoutDays')
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name']);

        return response()->json([
            'data' => $splits->map(fn(WorkoutSplit $split) => [
                'id' => $split->id,
                'label' => $split->name,
                'details' => __('interface.days_count', ['count' => $split->workout_days_count]),
                'url' => route('workout-splits.show', $split),
            ]),
        ]);
    }
}
