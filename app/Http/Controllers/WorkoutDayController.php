<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\DeplaceElements;
use App\Http\Requests\EnregistrerJourRequest;
use App\Models\WorkoutDay;
use App\Models\WorkoutSplit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class WorkoutDayController extends Controller
{
    use DeplaceElements;

    public function create(WorkoutSplit $workoutSplit): View
    {
        Gate::authorize('update', $workoutSplit);

        return view('workout-days.create', ['split' => $workoutSplit, 'jour' => null]);
    }

    public function store(EnregistrerJourRequest $request, WorkoutSplit $workoutSplit): RedirectResponse
    {
        Gate::authorize('update', $workoutSplit);

        $jour = $workoutSplit->workoutDays()->create([
            ...$request->validated(),
            'user_id' => $workoutSplit->user_id,
            'position' => ($workoutSplit->workoutDays()->max('position') ?? -1) + 1,
        ]);

        return $this->retourAuJour($jour)->with('status', __('interface.day_created'));
    }

    public function edit(WorkoutDay $day): View
    {
        Gate::authorize('update', $day->workoutSplit);

        return view('workout-days.edit', ['split' => $day->workoutSplit, 'jour' => $day]);
    }

    public function update(EnregistrerJourRequest $request, WorkoutDay $day): RedirectResponse
    {
        Gate::authorize('update', $day->workoutSplit);

        $day->update($request->validated());

        return $this->retourAuJour($day)->with('status', __('interface.day_updated'));
    }

    public function destroy(WorkoutDay $day): RedirectResponse
    {
        $split = $day->workoutSplit;
        Gate::authorize('update', $split);

        $day->delete();

        return redirect()->route('workout-splits.show', $split)
            ->with('status', __('interface.day_deleted'));
    }

    public function move(Request $request, WorkoutDay $day): RedirectResponse
    {
        $split = $day->workoutSplit;
        Gate::authorize('update', $split);

        $direction = $request->validate(['direction' => ['required', Rule::in(['up', 'down'])]])['direction'];
        $this->deplacer($split->workoutDays()->get(), $day, $direction);

        return $this->retourAuJour($day);
    }

    private function retourAuJour(WorkoutDay $jour): RedirectResponse
    {
        return redirect()->to(route('workout-splits.show', $jour->workout_split_id) . '#jour-' . $jour->id);
    }
}
