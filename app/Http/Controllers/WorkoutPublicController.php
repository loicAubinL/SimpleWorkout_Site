<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WorkoutPublicController extends Controller
{
    public function __invoke(Request $request): View
    {
        $donnees = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $terme = trim($donnees['q'] ?? '');

        $splits = WorkoutSplit::public()
            ->with('user:id,name')
            ->withCount(['workoutDays', 'exercises', 'savedBy'])
            ->when($terme !== '', function ($requete) use ($terme) {
                $motif = '%' . addcslashes($terme, '%_\\') . '%';
                $requete->where(function ($q) use ($motif) {
                    $q->where('name', 'like', $motif)
                        ->orWhere('description', 'like', $motif)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $motif))
                        ->orWhereHas('workoutDays', fn ($j) => $j->where('name', 'like', $motif))
                        ->orWhereHas('exercises', fn ($e) => $e->where('exercises.name', 'like', $motif));
                });
            })
            ->orderByDesc('saved_by_count')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $sauvegardes = $request->user()?->savedWorkouts()->pluck('workout_splits.id')->all() ?? [];

        return view('workout-splits.publics', compact('splits', 'terme', 'sauvegardes'));
    }
}
