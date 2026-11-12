<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\LoggedSet;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class HistoriqueController extends Controller
{
    /**
     * Historique : une carte par jour de séance, plus la liste des exercices suivis.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        /** @var LengthAwarePaginator $dates */
        $dates = $user->loggedSets()
            ->selectRaw('DATE(logged_at) as date_seance, COUNT(*) as nb_series')
            ->groupBy('date_seance')
            ->orderByDesc('date_seance')
            ->paginate(8);

        $series = $dates->isEmpty() ? collect() : $user->loggedSets()
            ->with('exercise.workoutDay.workoutSplit:id,name')
            ->where(function ($q) use ($dates) {
                foreach ($dates as $ligne) {
                    $q->orWhereDate('logged_at', $ligne->date_seance);
                }
            })
            ->orderBy('logged_at')
            ->get()
            ->groupBy(fn (LoggedSet $s) => $s->logged_at->toDateString());

        $seances = $dates->getCollection()->map(function ($ligne) use ($series) {
            $duJour = $series->get($ligne->date_seance, collect());

            return (object) [
                'date' => \Illuminate\Support\Carbon::parse($ligne->date_seance),
                'jours' => $duJour->map(fn ($s) => $s->exercise->workoutDay->name)->unique()->values(),
                'exercices' => $duJour->groupBy('exercise_id')->map(fn (Collection $s) => (object) [
                    'exercice' => $s->first()->exercise,
                    'series' => $s->sortBy('set_number')->values(),
                ])->values(),
                'volume' => $duJour->sum(fn ($s) => (float) $s->weight * $s->reps),
                'nb_series' => $ligne->nb_series,
            ];
        });

        $exercicesSuivis = Exercise::query()
            ->join('logged_sets', 'logged_sets.exercise_id', '=', 'exercises.id')
            ->where('logged_sets.user_id', $user->id)
            ->groupBy('exercises.id', 'exercises.name')
            ->orderByDesc(DB::raw('MAX(logged_sets.logged_at)'))
            ->get([
                'exercises.id',
                'exercises.name',
                DB::raw('MAX(logged_sets.weight) as meilleure_charge'),
                DB::raw('COUNT(DISTINCT DATE(logged_sets.logged_at)) as nb_seances'),
                DB::raw('MAX(logged_sets.logged_at) as derniere'),
            ]);

        $stats = [
            'seances' => $user->loggedSets()->distinct()->count(DB::raw('DATE(logged_at)')),
            'semaine' => $user->loggedSets()->where('logged_at', '>=', now()->startOfWeek())
                ->distinct()->count(DB::raw('DATE(logged_at)')),
            'series' => $user->loggedSets()->count(),
        ];

        return view('history.index', compact('dates', 'seances', 'exercicesSuivis', 'stats'));
    }

    /**
     * Progression d'un exercice : meilleure charge, volume et 1RM estimé par séance.
     */
    public function progress(Request $request, Exercise $exercise): View
    {
        $exercise->load('workoutDay.workoutSplit');
        Gate::authorize('train', $exercise->workoutDay->workoutSplit);

        $seances = $request->user()->loggedSets()
            ->where('exercise_id', $exercise->id)
            ->orderBy('logged_at')
            ->get()
            ->groupBy(fn (LoggedSet $s) => $s->logged_at->toDateString())
            ->map(function (Collection $series, string $date) {
                $meilleure = $series->sortByDesc(fn ($s) => [(float) $s->weight, $s->reps])->first();

                return (object) [
                    'date' => \Illuminate\Support\Carbon::parse($date),
                    'meilleure_charge' => $meilleure->weight !== null ? (float) $meilleure->weight : null,
                    'reps' => $meilleure->reps,
                    'nb_series' => $series->count(),
                    'volume' => $series->sum(fn ($s) => (float) $s->weight * $s->reps),
                    // Formule d'Epley : charge × (1 + reps / 30)
                    'un_rm' => $series->max(fn ($s) => $s->weight !== null ? (float) $s->weight * (1 + $s->reps / 30) : 0),
                ];
            })
            ->values();

        return view('history.progress', ['exercice' => $exercise, 'seances' => $seances]);
    }
}
