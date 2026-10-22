<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use App\Http\Requests\EnregistrerWorkoutSplitRequest;
use Illuminate\Http\RedirectResponse;

class WorkoutSplitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $splits = $this->utilisateurCourant()
            ->workoutSplits()
            ->withCount('workoutDays')
            ->latest()
            ->paginate(10);

        return view('workout-splits.index', compact('splits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('workout-splits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnregistrerWorkoutSplitRequest $request): RedirectResponse
    {
        $donnees = $request->validated();
        $user = $this->utilisateurCourant();

        $split = DB::transaction(function () use ($user, $donnees) {
            if ($donnees['is_active']) {
                $user->workoutSplits()->update(['is_active' => false]);
            }

            return $user->workoutSplits()->create($donnees);
        });

        return redirect()->route('workout-splits.show', $split)
            ->with('status', 'Workout créé.');
    }
    /**
     * Display the specified resource.
     */
    public function show(WorkoutSplit $workoutSplit): View
    {
        abort_if($workoutSplit->user_id !== $this->utilisateurCourant()->id, 404);

        $workoutSplit->load(['workoutDays' => fn($q) => $q->orderBy('position')]);

        return view('workout-splits.show', ['split' => $workoutSplit]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkoutSplit $workoutSplit): View
    {
        $this->verifierProprietaire($workoutSplit);

        return view('workout-splits.edit', ['split' => $workoutSplit]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(EnregistrerWorkoutSplitRequest $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        $this->verifierProprietaire($workoutSplit);
        $donnees = $request->validated();

        DB::transaction(function () use ($workoutSplit, $donnees) {
            if ($donnees['is_active']) {
                $workoutSplit->user->workoutSplits()
                    ->whereKeyNot($workoutSplit->id)
                    ->update(['is_active' => false]);
            }

            $workoutSplit->update($donnees);
        });

        return redirect()->route('workout-splits.show', $workoutSplit)
            ->with('status', 'Workout modifié.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkoutSplit $workoutSplit): RedirectResponse
    {
        $this->verifierProprietaire($workoutSplit);

        $workoutSplit->delete();

        return redirect()->route('workout-splits.index')
            ->with('status', 'Workout supprimé.');
    }

    private function utilisateurCourant(): User
    {
        return User::query()->orderBy('id')->firstOrFail();
    }

    private function verifierProprietaire(WorkoutSplit $workoutSplit): void
    {
        abort_if($workoutSplit->user_id !== $this->utilisateurCourant()->id, 404);
    }
}
