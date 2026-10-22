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
        $split = $this->utilisateurCourant()->workoutSplits()->create($request->validated());

        return redirect()->route('workout-splits.show', $split)
            ->with('status', 'Workout crée.');
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

        $workoutSplit->update($request->validated());

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
