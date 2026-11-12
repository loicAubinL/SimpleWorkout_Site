<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnregistrerWorkoutSplitRequest;
use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class WorkoutSplitController extends Controller
{
    public function index(Request $request): View
    {
        $splits = $request->user()
            ->workoutSplits()
            ->withCount(['workoutDays', 'exercises'])
            ->orderByDesc('is_active')
            ->latest()
            ->paginate(12);

        return view('workout-splits.index', compact('splits'));
    }

    public function create(): View
    {
        return view('workout-splits.create');
    }

    public function store(EnregistrerWorkoutSplitRequest $request): RedirectResponse
    {
        $donnees = $request->validated();
        $user = $request->user();

        $split = DB::transaction(function () use ($user, $donnees) {
            if ($donnees['is_active']) {
                $user->workoutSplits()->update(['is_active' => false]);
            }

            return $user->workoutSplits()->create($donnees);
        });

        return redirect()->route('workout-splits.show', $split)
            ->with('status', __('interface.created'));
    }

    public function show(Request $request, WorkoutSplit $workoutSplit): View
    {
        Gate::authorize('view', $workoutSplit);

        $workoutSplit->load(['user:id,name', 'workoutDays.exercises'])
            ->loadCount('savedBy');

        $user = $request->user();

        return view('workout-splits.show', [
            'split' => $workoutSplit,
            'estSauvegarde' => $user ? $user->savedWorkouts()->whereKey($workoutSplit->id)->exists() : false,
            'jourCourant' => $workoutSplit->currentDay(),
        ]);
    }

    public function edit(WorkoutSplit $workoutSplit): View
    {
        Gate::authorize('update', $workoutSplit);

        return view('workout-splits.edit', ['split' => $workoutSplit]);
    }

    public function update(EnregistrerWorkoutSplitRequest $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        Gate::authorize('update', $workoutSplit);
        $donnees = $request->validated();

        DB::transaction(function () use ($workoutSplit, $donnees) {
            if ($donnees['is_active']) {
                $this->desactiverLesAutres($workoutSplit);
            }

            $workoutSplit->update($donnees);
        });

        return redirect()->route('workout-splits.show', $workoutSplit)
            ->with('status', __('interface.updated'));
    }

    public function destroy(Request $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        Gate::authorize('delete', $workoutSplit);
        $proprietaire = $workoutSplit->user_id;
        $ancienFichier = $workoutSplit->program_path;

        $workoutSplit->delete();

        // Fichier JSON hérité du chapitre 8, s'il existe encore.
        if ($ancienFichier) {
            Storage::disk('public')->delete($ancienFichier);
        }

        // Un responsable qui supprime le workout d'un autre compte revient à la fiche de ce compte.
        if ($proprietaire !== $request->user()->id && $request->user()->isManager()) {
            return redirect()->route('admin.users.show', $proprietaire)
                ->with('status', __('interface.deleted'));
        }

        return redirect()->route('workout-splits.index')
            ->with('status', __('interface.deleted'));
    }

    /**
     * Définir comme workout actif (un seul à la fois par utilisateur).
     */
    public function activate(WorkoutSplit $workoutSplit): RedirectResponse
    {
        Gate::authorize('update', $workoutSplit);

        DB::transaction(function () use ($workoutSplit) {
            $this->desactiverLesAutres($workoutSplit);
            $workoutSplit->update(['is_active' => true]);
        });

        return back()->with('status', __('interface.activated'));
    }

    private function desactiverLesAutres(WorkoutSplit $workoutSplit): void
    {
        WorkoutSplit::where('user_id', $workoutSplit->user_id)
            ->whereKeyNot($workoutSplit->id)
            ->update(['is_active' => false]);
    }
}
