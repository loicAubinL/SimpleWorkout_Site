<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccueilController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $derniers = WorkoutSplit::public()
            ->with('user:id,name')
            ->withCount(['workoutDays', 'exercises'])
            ->latest()
            ->limit(3)
            ->get();

        if (! $user) {
            return view('accueil', compact('derniers'));
        }

        $actif = $user->activeSplit()->with('workoutDays.exercises')->first();
        $jourDuJour = $actif?->currentDay();

        $faitAujourdhui = $jourDuJour && $user->loggedSets()
            ->whereIn('exercise_id', $jourDuJour->exercises->pluck('id'))
            ->whereDate('logged_at', today())
            ->exists();

        $stats = [
            'semaine' => $user->loggedSets()->where('logged_at', '>=', now()->startOfWeek())
                ->distinct()->count(DB::raw('DATE(logged_at)')),
            'derniere' => $user->loggedSets()->max('logged_at'),
            'workouts' => $user->workoutSplits()->count(),
            'sauvegardes' => $user->savedWorkouts()->count(),
        ];

        return view('accueil', compact('derniers', 'actif', 'jourDuJour', 'faitAujourdhui', 'stats'));
    }
}
