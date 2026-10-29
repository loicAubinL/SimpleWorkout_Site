<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use App\Http\Requests\EnregistrerWorkoutSplitRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

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
        $donnees = $request->safe()->except('program');
        $user = $this->utilisateurCourant();
        $chemin = $this->stockerProgramme($request->file('program'));

        try {
            $split = DB::transaction(function () use ($user, $donnees, $chemin) {
                if ($donnees['is_active']) {
                    $user->workoutSplits()->update(['is_active' => false]);
                }

                return $user->workoutSplits()->create([...$donnees, 'program_path' => $chemin]);
            });
        } catch (Throwable $exception) {
            if ($chemin) {
                Storage::disk('public')->delete($chemin);
            }
            throw $exception;
        }

        return redirect()->route('workout-splits.show', $split)
            ->with('status', 'Workout créé.');
    }
    /**
     * Display the specified resource.
     */
    public function show(WorkoutSplit $workoutSplit): View
    {
        $this->verifierProprietaire($workoutSplit);

        $workoutSplit->load(['workoutDays' => fn($q) => $q->orderBy('position')]);

        $apercu = null;
        if ($workoutSplit->program_path && Storage::disk('public')->exists($workoutSplit->program_path)) {
            $apercu = json_encode(
                json_decode(Storage::disk('public')->get($workoutSplit->program_path)),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
        }

        return view('workout-splits.show', ['split' => $workoutSplit, 'apercu' => $apercu]);
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
        $donnees = $request->safe()->except('program');
        $nouveauChemin = $this->stockerProgramme($request->file('program'));
        $ancienChemin = $workoutSplit->program_path;

        try {
            DB::transaction(function () use ($workoutSplit, $donnees, $nouveauChemin) {
                if ($donnees['is_active']) {
                    $workoutSplit->user->workoutSplits()
                        ->whereKeyNot($workoutSplit->id)
                        ->update(['is_active' => false]);
                }

                $workoutSplit->update([
                    ...$donnees,
                    ...($nouveauChemin ? ['program_path' => $nouveauChemin] : []),
                ]);
            });
        } catch (Throwable $exception) {
            if ($nouveauChemin) {
                Storage::disk('public')->delete($nouveauChemin);
            }
            throw $exception;
        }

        if ($nouveauChemin && $ancienChemin) {
            Storage::disk('public')->delete($ancienChemin);
        }

        return redirect()->route('workout-splits.show', $workoutSplit)
            ->with('status', 'Workout modifié.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkoutSplit $workoutSplit): RedirectResponse
    {
        $this->verifierProprietaire($workoutSplit);
        $chemin = $workoutSplit->program_path;

        $workoutSplit->delete();

        if ($chemin) {
            Storage::disk('public')->delete($chemin);
        }

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

    private function stockerProgramme(?UploadedFile $fichier): ?string
    {
        return $fichier?->storeAs('programmes', Str::random(40) . '.json', 'public');
    }

    public function destroyProgram(WorkoutSplit $workoutSplit): RedirectResponse
    {
        $this->verifierProprietaire($workoutSplit);
        $chemin = $workoutSplit->program_path;

        if ($chemin) {
            $workoutSplit->update(['program_path' => null]);
            Storage::disk('public')->delete($chemin);
        }

        return redirect()->route('workout-splits.edit', $workoutSplit)
            ->with('status', 'Programme retiré.');
    }
}
