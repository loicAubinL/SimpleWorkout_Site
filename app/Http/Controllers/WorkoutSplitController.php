<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function utilisateurCourant(): User
    {
        return User::query()->orderBy('id')->firstOrFail();
    }
}
